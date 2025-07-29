<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\NotificationLog;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phone;
    protected $message;

    public function __construct($phone, $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }

    public function handle()
    {
        if (!$this->phone || !$this->message) {
            Log::warning('SMS job missing phone or message.');
            $this->logCommunication(400, ["missing phone or message."]);
            return;
        }


        $config = config('systemInfo.sms');
        if (strlen($config['sender']) < 3 || strlen($config['sender']) > 11 || str_contains($config['sender'], ' ')) {
            Log::error('Invalid Sender ID: Must be 3-11 characters with no spaces.', [
                'sender' => $config['sender']
            ]);
            $this->logCommunication(400, ['sender' => $config['sender'], 'Invalid Sender ID: Must be 3-11 characters with no spaces.']);

            return;
        }

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->post(rtrim($config['url'], '/'), [
                'api_token'  => $config['api_token'],
                'api_secret' => $config['api_secret'],
                'to'         => $this->phone,
                'from'       => $config['sender'],
                'message'    => $this->message,
            ]);
        } catch (\Exception $e) {
            Log::error('Something went wrong while sending SMS.', [
                'error' => $e->getMessage()
            ]);
            $this->logCommunication(400, ['sender' => $config['sender'], 'Something went wrong while sending SMS.', 'error' => $e->getMessage()]);
            return;
        }

        if ($response->successful() && str_contains(strtolower($response->body()), 'ok')) {

            $this->logCommunication($response->status(), ['message' => 'SMS sent successfully to ' . $this->phone]);

            Log::info('SMS sent successfully to ' . $this->phone);
        } else {

            $this->logCommunication($response->status(), [$response->body()]);

            Log::error(' SMS sending failed', [
                'to' => $this->phone,
                'message' => $this->message,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
        }
    }

    private function logCommunication(int $statusCode, array $response): void
    {
        NotificationLog::create([
            'type' => 'sms',
            'message' => $this->message,
            'phone' => $this->phone,
            'status_code' => $statusCode,
            'response' => $response,
        ]);
    }
}
