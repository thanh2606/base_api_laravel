<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use \Illuminate\Support\Facades\Log;

class SendEmailOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $email;

    protected string $code;

    public function __construct(string $email, string $code)
    {
        $this->email = $email;
        $this->code = $code;
    }

    public function handle(): void
    {
        try {
            Mail::raw("Mã OTP của bạn là: {$this->code}", function ($message) {
                $message->to($this->email)->subject('Mã OTP xác thực');
            });
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
