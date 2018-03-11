<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FollowToFollowersNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $following;

    /**
     * FollowToFollowersNotification constructor.
     * @param User $user
     * @param User $following
     */
    public function __construct(User $user,User $following)
    {
        $this->user = $user;
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
            'user_id' => $this->user->id,
            'user_avatar' => $this->user->avatar,
            'user_name' => $this->user->name,
            'following_id' => $this->following->id,
            'following_avatar' => $this->following->avatar,
            'following_name' => $this->following->name,
        ];
    }
}
