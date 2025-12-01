<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSmsOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $phone;

    protected string $code;

    public function __construct(string $phone, string $code)
    {
        $this->phone = $phone;
        $this->code = $code;
        // $this->onQueue('otp');
    }

    public function handle(): void
    {
        // Gọi service SMS thực tế ở đây
        // app(\App\Services\SmsService::class)->send($this->phone, "Mã OTP của bạn là: {$this->code}");

        Log::info('Send SMS OTP', [
            'phone' => $this->phone,
            'code' => $this->code,
        ]);
    }
}
