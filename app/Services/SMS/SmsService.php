<?php

namespace App\Services\SMS;

class SmsService implements SmsServiceInterface
{
    public function sendVerificationCode(string $phone, string $code): void
    {
        // TODO: Tích hợp SMS provider thực tế
        // Ví dụ: Twilio, Nexmo, v.v.
        // throw new \RuntimeException('SMS provider not implemented');
    }
}
