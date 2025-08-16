<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendWhatsAppJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $whatsapp_number;
    protected $message;

    public function __construct($whatsapp_number, $message)
    {
        $this->whatsapp_number = $whatsapp_number;
        $this->message = $message;
    }

    public function handle()
    {
        if (!$this->whatsapp_number) {
            return;
        }

        $config = config('systemInfo.whatsapp');
        $url = rtrim($config['url'], '/') . "/{$config['phone_id']}/messages";

        $response = Http::withToken($config['api_token'])
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $this->whatsapp_number,
                'type' => $config['type'],
                'text' => [
                    'body' => $this->message
                ]
            ]);

        if ($response->failed()) {
            Log::error('WhatsApp message failed', [
                'to' => $this->whatsapp_number,
                'response' => $response->body()
            ]);
        } else {
            Log::info('WhatsApp message sent successfully to ' . $this->whatsapp_number);
        }
    }
}
