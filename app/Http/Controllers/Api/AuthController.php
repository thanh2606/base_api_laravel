<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\DeleteUnverifiedUserJob;
use App\Jobs\SendEmailOtpJob;
use App\Jobs\SendSmsOtpJob;
use App\Models\PhoneVerificationCode;
use App\Models\User;
use App\Services\SMS\SmsServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Config: thời gian sống OTP, delay resend, giới hạn số lần
    private int $otpTtlMinutes = 5;

    private int $resendDelaySeconds = 60;   // không cho resend liên tục < 60s

    private int $maxOtpAttempts = 5;

    public function __construct(private SmsServiceInterface $smsService) {}

    /**
     * Đăng ký tài khoản
     *
     * Đăng ký tài khoản mới bằng số điện thoại và mật khẩu.
     * Sau khi đăng ký, hệ thống sẽ gửi mã OTP đến số điện thoại để xác thực.
     *
     * @group Auth
     *
     * @bodyParam identifier string required số điện thoại hoặc email của người dùng. Ví dụ: 0901234567 hoặc test@gmail.com
     * @bodyParam password string required Mật khẩu. Tối thiểu 6 ký tự. Ví dụ: password123
     * @bodyParam password_confirmation string required Nhập lại mật khẩu. Phải trùng với password.
     *
     * @response 201 {
     *   "message": "Đăng ký thành công, vui lòng nhập mã OTP được gửi qua SMS để xác thực số điện thoại.",
     *   "identifier": "0901234567"
     * }
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "identifier": [
     *       "The email has already been taken."
     *     ],
     *     "password": [
     *       "The password confirmation does not match."
     *     ]
     *   }
     * }
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'identifier' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Check email
                    if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        // unique email
                        if (User::where('email', $value)->exists()) {
                            $fail('The email has already been taken.');
                        }

                        return;
                    }

                    if (!preg_match('/^0[0-9]{9}$/', $value)) {
                        $fail('The phone not true');

                        return;
                    }

                    // unique phone
                    if (User::where('phone', $value)->exists()) {
                        $fail('The phone has already been taken.');
                    }
                },
            ],
            'password' => 'required|string|min:6|confirmed',
        ]);

        return DB::transaction(function () use ($data) {
            $identifier = $data['identifier'];

            $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);

            $userData = [
                'password' => Hash::make($data['password']),
            ];

            if ($isEmail) {
                $user = User::create([
                    'email' => $identifier,
                    ...$userData,
                ]);
            } else {
                $user = User::create([
                    'phone' => $identifier,
                    ...$userData,
                ]);
            }

            if ($isEmail) {
                $this->generateAndSendEmailOtp($user->email);
                $message = 'Đăng ký thành công, vui lòng nhập mã OTP được gửi qua email để xác thực.';
            } else {
                $this->generateAndSendOtp($user->phone);
                $message = 'Đăng ký thành công, vui lòng nhập mã OTP được gửi qua SMS để xác thực số điện thoại.';
            }

            DeleteUnverifiedUserJob::dispatch($user->id)->delay(now()->addMinutes(20));

            return response()->json([
                'message' => $message,
                'identifier' => $identifier,
            ], 201);
        });
    }

    /**
     * Xác thực OTP
     *
     * Xác thực mã OTP đã gửi tới số điện thoại sau khi đăng ký.
     * Nếu mã hợp lệ, số điện thoại sẽ được đánh dấu đã xác thực và hệ thống trả về token đăng nhập.
     *
     * @group Auth
     *
     * @bodyParam phone string required Số điện thoại đã đăng ký. Ví dụ: 0901234567
     * @bodyParam code string required Mã OTP gồm 6 chữ số. Ví dụ: 123456
     *
     * @response 200 {
     *   "message": "Xác thực thành công.",
     *   "token": "1|fFsgvbKc3P3xxxxxxxxxxxxxxxxxxxxx",
     *   "user": {
     *     "id": 1,
     *     "name": "Nguyen Van A",
     *     "phone": "0901234567",
     *     "phone_verified_at": "2025-01-01T12:00:00.000000Z",
     *     "created_at": "2025-01-01T11:00:00.000000Z",
     *     "updated_at": "2025-01-01T11:00:00.000000Z"
     *   }
     * }
     * @response 422 scenario="Mã OTP sai" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "code": [
     *       "Mã OTP không chính xác."
     *     ]
     *   }
     * }
     * @response 422 scenario="Mã OTP hết hạn hoặc đã dùng" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "code": [
     *       "Mã đã được sử dụng hoặc đã hết hạn."
     *     ]
     *   }
     * }
     * @response 422 scenario="Vượt quá số lần nhập mã" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "code": [
     *       "Bạn đã vượt quá số lần nhập mã cho phép."
     *     ]
     *   }
     * }
     */
    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string',
            'code' => 'required|string',
        ]);

        $verification = PhoneVerificationCode::where('phone', $data['phone'])
            ->orderByDesc('id')
            ->first();

        if (! $verification) {
            throw ValidationException::withMessages([
                'code' => ['Mã không tồn tại hoặc đã hết hạn.'],
            ]);
        }

        if ($verification->used_at || $verification->expires_at->isPast()) {
            throw ValidationException::withMessages([
                'code' => ['Mã đã được sử dụng hoặc đã hết hạn.'],
            ]);
        }

        if ($verification->attempts >= $this->maxOtpAttempts) {
            throw ValidationException::withMessages([
                'code' => ['Bạn đã vượt quá số lần nhập mã cho phép.'],
            ]);
        }

        // Tăng số lần thử
        $verification->increment('attempts');

        if ($verification->code !== $data['code']) {
            throw ValidationException::withMessages([
                'code' => ['Mã OTP không chính xác.'],
            ]);
        }

        // Đúng mã
        $verification->update([
            'used_at' => now(),
        ]);

        $user = User::where('phone', $data['phone'])->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'phone' => ['User không tồn tại.'],
            ]);
        }

        if (! $user->phone_verified_at) {
            $user->phone_verified_at = now();
            $user->save();
        }

        // login luôn sau khi verify (tùy bạn quyết)
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Xác thực thành công.',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Gửi lại mã OTP
     *
     * Gửi lại mã OTP cho số điện thoại đã đăng ký.
     * Có giới hạn thời gian giữa các lần gửi (VD: 60 giây).
     *
     * @group Auth
     *
     * @bodyParam phone string required Số điện thoại đã đăng ký. Ví dụ: 0901234567
     *
     * @response 200 {
     *   "message": "Đã gửi lại mã OTP.",
     *   "phone": "0901234567"
     * }
     * @response 422 scenario="Gửi lại quá sớm" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "phone": [
     *       "Vui lòng đợi 30 giây nữa trước khi yêu cầu mã mới."
     *     ]
     *   }
     * }
     * @response 422 scenario="Số điện thoại không tồn tại" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "phone": [
     *       "The selected phone is invalid."
     *     ]
     *   }
     * }
     */
    public function resendOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string|exists:users,phone',
        ]);

        $last = PhoneVerificationCode::where('phone', $data['phone'])
            ->orderByDesc('id')
            ->first();

        if ($last && $last->created_at->gt(now()->subSeconds($this->resendDelaySeconds))) {
            $secondsLeft = $last->created_at->addSeconds($this->resendDelaySeconds)->diffInSeconds(now());
            throw ValidationException::withMessages([
                'phone' => ["Vui lòng đợi {$secondsLeft} giây nữa trước khi yêu cầu mã mới."],
            ]);
        }

        $code = $this->generateAndSendOtp($data['phone']);

        return response()->json([
            'message' => 'Đã gửi lại mã OTP.',
            'phone' => $data['phone'],
            // 'debug_code' => $code,
        ]);
    }

    /**
     * Đăng nhập
     *
     * Đăng nhập bằng số điện thoại và mật khẩu.
     * Trả về Bearer Token (Sanctum) để dùng cho các API cần auth.
     *
     * @group Auth
     *
     * @bodyParam phone string required Số điện thoại đã đăng ký. Ví dụ: 0901234567
     * @bodyParam password string required Mật khẩu. Ví dụ: password123
     *
     * @response 200 {
     *   "message": "Đăng nhập thành công.",
     *   "token": "1|fFsgvbKc3P3xxxxxxxxxxxxxxxxxxxxx",
     *   "user": {
     *     "id": 1,
     *     "name": "Nguyen Van A",
     *     "phone": "0901234567",
     *     "phone_verified_at": "2025-01-01T12:00:00.000000Z",
     *     "created_at": "2025-01-01T11:00:00.000000Z",
     *     "updated_at": "2025-01-01T11:00:00.000000Z"
     *   }
     * }
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "phone": [
     *       "Thông tin đăng nhập không chính xác."
     *     ]
     *   }
     * }
     * @response 422 scenario="Chưa verify số điện thoại" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "phone": [
     *       "Số điện thoại chưa được xác thực."
     *     ]
     *   }
     * }
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $data['phone'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'phone' => ['Thông tin đăng nhập không chính xác.'],
            ]);
        }

        if (! $user->phone_verified_at) {
            throw ValidationException::withMessages([
                'phone' => ['Số điện thoại chưa được xác thực.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công.',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /**
     * Đăng xuất
     *
     * Thu hồi (revoke) Bearer token hiện tại của user.
     *
     * @group Auth
     *
     * @authenticated
     *
     * @response 200 {
     *   "message": "Đăng xuất thành công."
     * }
     * @response 401 {
     *   "message": "Unauthenticated."
     * }
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Đăng xuất thành công.',
        ]);
    }

    /**
     * Thông tin user hiện tại
     *
     * Lấy thông tin user tương ứng với Bearer token đang sử dụng.
     *
     * @group Auth
     *
     * @authenticated
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Nguyen Van A",
     *   "phone": "0901234567",
     *   "phone_verified_at": "2025-01-01T12:00:00.000000Z",
     *   "created_at": "2025-01-01T11:00:00.000000Z",
     *   "updated_at": "2025-01-01T11:00:00.000000Z"
     * }
     * @response 401 {
     *   "message": "Unauthenticated."
     * }
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Đổi mật khẩu
     *
     * Đổi mật khẩu cho user đang đăng nhập.
     *
     * @group Auth
     *
     * @authenticated
     *
     * @bodyParam current_password string required Mật khẩu hiện tại. Ví dụ: oldpass123
     * @bodyParam new_password string required Mật khẩu mới. Tối thiểu 6 ký tự. Ví dụ: newpass123
     * @bodyParam new_password_confirmation string required Nhập lại mật khẩu mới. Phải trùng với new_password.
     *
     * @response 200 {
     *   "message": "Đổi mật khẩu thành công."
     * }
     * @response 422 scenario="Sai mật khẩu hiện tại" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "current_password": [
     *       "Mật khẩu hiện tại không đúng."
     *     ]
     *   }
     * }
     * @response 422 scenario="Nhập lại mật khẩu mới không khớp" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "new_password": [
     *       "The new password confirmation does not match."
     *     ]
     *   }
     * }
     * @response 401 {
     *   "message": "Unauthenticated."
     * }
     */
    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (! Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Mật khẩu hiện tại không đúng.'],
            ]);
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        return response()->json([
            'message' => 'Đổi mật khẩu thành công.',
        ]);
    }

    /**
     * Helper: tạo OTP + gửi SMS
     */
    private function generateAndSendOtp(string $phone): void
    {
        $code = $this->generateOtpCode();

        PhoneVerificationCode::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes($this->otpTtlMinutes),
        ]);

        SendSmsOtpJob::dispatch($phone, $code);
    }

    private function generateOtpCode(): string
    {
        return (string) random_int(100000, 999999);
    }

    protected function generateAndSendEmailOtp(string $email): void
    {
        $code = $this->generateOtpCode();

        PhoneVerificationCode::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => now()->addMinutes($this->otpTtlMinutes),
        ]);

        SendEmailOtpJob::dispatch($email, $code);
    }
}
