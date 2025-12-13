<?php

namespace App\Jobs;

use App\Services\SmsService; // Make sure to import the SmsService
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected $phone;
    protected $message;
    protected $masking;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phone, $message, $masking)
    {
        $this->phone = $phone;
        $this->message = $message;
        $this->masking = $masking;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $smsService = new SmsService(
            $this->phone,
            $this->message,
            $this->masking
        );

        $smsService->singleSms();
    }
}
