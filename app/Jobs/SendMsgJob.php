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

class SendMsgJob implements ShouldQueue
{
    public $type = 'mail';
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public $email, public $sms_number, public $whatsapp_number, public $message)
    {
       
    }

    public function handle()
    {
        \Illuminate\Support\Facades\Log::info('Notification sent');
        switch ($this->type) {
            case 'mail':
                SendMailJob::dispatch($this->email, $this->message);
                break;
            case 'sms':
                SendSmsJob::dispatch($this->sms_number, $this->message);
                break;
            case 'whatsapp':
                SendWhatsAppJob::dispatch($this->whatsapp_number, $this->message);
                break;
        }
    }
}
