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
            Mail::raw($this->message, function ($mail) {
                $mail->to($this->email)
                     ->subject($this->subject);
            });

            $this->logCommunication(200, ['message' => 'Email sent successfully']);
            Log::info("Email sent to: {$this->email}");
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error("Job failed for email {$this->email}: " . $exception->getMessage());

        $this->logCommunication(500, [
            'message' => "Failed to send email to {$this->email}",
            'exception' => $exception->getMessage(),
        ]);
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
