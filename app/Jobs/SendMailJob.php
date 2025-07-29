<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use App\NotificationLog;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $email, $message, $subject;

    public function __construct($email, $message, $subject)
    {
        $this->email = $email;
        $this->message = $message;
        $this->subject = $subject;
    }

    public function handle()
    {
        if ($this->email) {
            try {
                Mail::raw($this->message, function ($mail) {
                    $mail->to($this->email)
                        ->subject($this->subject);
                });

                $this->logCommunication(200, ['message' => 'Email sent successfully']);
                Log::info("Email sent to: {$this->email}");
                
            } catch (\Exception $e) {
                Log::error("Failed to send email to {$this->email}. Error: " . $e->getMessage());
                $this->logCommunication(400, ['message' => "Failed to send email to {$this->email}. Error: " . $e->getMessage()]);
            }
        }
    }

    private function logCommunication(int $statusCode, array $response): void
    {
        NotificationLog::create([
            'type' => 'mail',
            'message' => $this->message,
            'email' => $this->email,
            'status_code' => $statusCode,
            'response' => $response,
        ]);
    }
}
