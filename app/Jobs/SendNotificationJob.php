<?php

namespace App\Jobs;

use App\Model\Notification;
use App\Model\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $notificationMessage;
    protected $link;

    /**
     * Create a new job instance.
     *
     * @param int $userId
     * @param string $notificationMessage
     * @param string|null $link
     */
    public function __construct(int $userId, string $notificationMessage, string $link)
    {
        $this->userId = $userId;
        $this->notificationMessage = $notificationMessage;
        $this->link = $link;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get the user
        $user = User::find($this->userId);

        // Create the notification
        Notification::create([
            'notification' => $this->notificationMessage,
            'link' => $this->link,
            'is_read' => false,
            'user_id' => $user->id,
        ]);

        // Optionally, you can send a real-time notification or perform any other action here
        // Example: $user->notify(new SomeNotification($this->notificationMessage));
    }
}
