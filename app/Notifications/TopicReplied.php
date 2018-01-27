<?php

namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TopicReplied extends Notification
{
    use Queueable;

    public $replay;

    /**
     * Create a new notification instance.
     * @param Reply $reply
     * @return void
     */
    public function __construct(Reply $reply)
    {
        $this->replay = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toDataBase($notifiable)
    {
        $topic = $this->replay->topic;

        $link = $topic->link('#reply', $this->replay->id);

        return [
            'reply_id' => $this->replay->id,
            'reply_content' => $this->replay->content,
            'user_id' => $this->replay->user->id,
            'user_name' => $this->replay->user->name,
            'user_avatar' => $this->replay->user->avatar,
            'topic_link' => $link,
            'topic_id' => $topic->id,
            'topic_title' => $topic->title
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
