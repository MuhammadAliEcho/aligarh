<?php

namespace App\Jobs;

use App\Jobs\SendMailJob;
use App\Jobs\SendSmsJob;
use App\Jobs\SendWhatsAppJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\NotificationsSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SendAttendanceJob implements ShouldQueue
{
 use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $emailSubject, $message;
    public Collection $notificationsSettings;

    public function __construct(
        public  $notificationsSettingsName,
        public  $name,
        public  $email,
        public  $sms_number,
        public  $whatsapp_number
    ) {
        $this->emailSubject = 'Email from ' . config('systemInfo.general.name');
        
        $settings = NotificationsSetting::where('name', $notificationsSettingsName)->first();
        
        $this->notificationsSettings = collect([
            'sms' => (bool)($settings->sms ?? false),
            'mail' => (bool)($settings->mail ?? false),
            'whatsapp' => (bool)($settings->whatsapp ?? false),
        ]);

        $this->message = $settings->message;
        $this->message = str_replace("{name}", $name,$this->message);
    }

    public function handle(): void
    {
        try {
            if ($this->sendOn('mail')) {
                SendMailJob::dispatch($this->email, $this->message, $this->emailSubject);
            }

            if ($this->sendOn('sms')) {
                SendSmsJob::dispatch($this->sms_number, $this->message);
            }

            // if ($this->sendOn('whatsapp')) {
            //     SendWhatsAppJob::dispatch($this->whatsapp_number, $this->message);
            // }
        } catch (\Throwable $e) {
            Log::error('SendMsgJob dispatch failed: ' . $e->getMessage(), [
                'email' => $this->email,
                'sms_number' => $this->sms_number,
                'whatsapp_number' => $this->whatsapp_number,
            ]);
        }
    }

    private function sendOn(string $type): bool
    {
        return $this->notificationsSettings->get($type, false);
    }
}
