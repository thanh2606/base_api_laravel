<?php

namespace App\Services\SMS;

interface SmsServiceInterface
{
    public function sendVerificationCode(string $phone, string $code): void;
}
