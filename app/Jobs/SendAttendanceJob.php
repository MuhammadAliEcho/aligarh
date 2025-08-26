<?php

namespace App\Jobs;

use App\Jobs\SendMailJob;
use App\Jobs\SendSmsJob;
use App\Jobs\SendWhatsAppJob;
use App\NotificationsSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SendAttendanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $emailSubject;
    public $message;
    public Collection $notificationsSettings;
    public bool $shouldSkip = false;

    public function __construct(
        public $notificationsSettingsName,
        public $name,
        public $email,
        public $sms_number,
        public $whatsapp_number
    ) {
        $this->emailSubject = 'Email from ' . config('systemInfo.general.name');

        $settings = NotificationsSetting::where('name', $this->notificationsSettingsName)->first();

        if (!$settings) {
            $this->shouldSkip = true;
            return;
        }

        $this->notificationsSettings = collect([
            'sms' => (bool)$settings->sms,
            'mail' => (bool)$settings->mail,
            'whatsapp' => (bool)$settings->whatsapp,
        ]);

        //variables parser
        $this->message = str_replace('{name}', $this->name, $settings->message ?? '');
    }

    public function handle(): void
    {
        if ($this->shouldSkip) {
            Log::info("SendAttendanceJob skipped: Notification settings '{$this->notificationsSettingsName}' not found.");
            return;
        }

        try {
            if ($this->shouldSend('mail')) {
                SendMailJob::dispatch($this->email, $this->message, $this->emailSubject);
            }

            if ($this->shouldSend('sms')) {
                SendSmsJob::dispatch($this->sms_number, $this->message);
            }

            // Uncomment when WhatsApp support is ready
            // if ($this->shouldSend('whatsapp')) {
            //     SendWhatsAppJob::dispatch($this->whatsapp_number, $this->message);
            // }

        } catch (\Throwable $e) {
            Log::error('SendAttendanceJob dispatch failed', [
                'error' => $e->getMessage(),
                'email' => $this->email,
                'sms_number' => $this->sms_number,
                'whatsapp_number' => $this->whatsapp_number,
            ]);
        }
    }

    private function shouldSend(string $channel): bool
    {
        return $this->notificationsSettings->get($channel, false);
    }
}
