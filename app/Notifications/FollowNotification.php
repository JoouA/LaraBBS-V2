<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FollowNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $follower;
    protected $following;

    /**
     * FollowNotification constructor.
     * @param User $follower
     * @param User $following
     */
    public function __construct(User $follower,User $following)
    {
        $this->follower = $follower;
        $this->following = $following;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'follower_id' => $this->follower->id,
            'follower_avatar' => $this->follower->avatar,
            'follower_name' => $this->follower->name,
            'following_id' => $this->following->id,
            'following_avatar' => $this->following->avatar,
            'following_name' => $this->following->name,
        ];
    }
}
