<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PhoneVerificationCode;
use App\Services\SMS\SmsService;
use App\Services\SMS\SmsServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Config: thời gian sống OTP, delay resend, giới hạn số lần
    private int $otpTtlMinutes = 5;
    private int $resendDelaySeconds = 60;   // không cho resend liên tục < 60s
    private int $maxOtpAttempts = 5;

    public function __construct(private SmsServiceInterface $smsService)
    {
    }

    /**
     * Đăng ký bằng phone + password
     * - Tạo user
     * - Tạo OTP
     * - Gửi SMS
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'phone'    => 'required|string|unique:users,phone',
            'name'     => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'phone'    => $data['phone'],
                'password' => Hash::make($data['password']),
            ]);

            $code = $this->generateAndSendOtp($user->phone);

            return response()->json([
                'message' => 'Đăng ký thành công, vui lòng nhập mã OTP được gửi qua SMS để xác thực số điện thoại.',
                'phone'   => $user->phone,
                // không trả code ra ngoài trong thực tế, chỉ để debug thì có thể thêm:
                // 'debug_code' => $code,
            ], 201);
        });
    }

    /**
     * Verify OTP cho số điện thoại (sau khi đăng ký).
     */
    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|string',
            'code'  => 'required|string',
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
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    /**
     * Resend OTP (giới hạn thời gian).
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
            'phone'   => $data['phone'],
            // 'debug_code' => $code,
        ]);
    }

    /**
     * Login bằng phone + password (chỉ cho user đã verify phone).
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'phone'    => 'required|string',
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
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    /**
     * Logout (revoke current token).
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
     * Xem thông tin user hiện tại.
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Đổi password (cần login).
     */
    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6|confirmed',
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
    private function generateAndSendOtp(string $phone): string
    {
        $code = (string) random_int(100000, 999999);

        PhoneVerificationCode::create([
            'phone'      => $phone,
            'code'       => $code,
            'expires_at' => now()->addMinutes($this->otpTtlMinutes),
        ]);

        $this->smsService->sendVerificationCode($phone, $code);

        return $code;
    }
}
