<?php

namespace App\Notifications;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReplyToFollowersNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reply;
    protected $user;

    /**
     * ReplyToFollowersNotification constructor.
     * @param User $user
     * @param Reply $reply
     */
    public function __construct(User $user,Reply $reply)
    {
        $this->user = $user;
        $this->reply = $reply;
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
        $topic = $this->reply->topic;

        $link = $topic->link(['#reply'.$this->reply->id]);

        // 谁reply了某一篇topic
        // 存入数据库中的数据
        return [
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_avatar' => $this->user->avatar,
            'topic_link' => $link,
            'topic_id' => $topic->id,
            'topic_title' => $topic->title,
        ];
    }
}
