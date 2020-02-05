<?php

namespace App\Notifications;

use App\Idea;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class CreateIdea extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  Idea $idea
     * @return array
     */
    public function via(Idea $idea)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  Idea $idea
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(Idea $idea)
    {
        $product = $idea->product;
        $workspace = $product->workspace;
        $link = url(config('app.client_url') . '/' . $workspace->alias . '/' . $product->slug . '/ideas/topic/' . $idea->id . '-' . $idea->slug);
        return (new MailMessage)
            ->line('New idea has been created on product "'.$product->name.'"')
            ->line($idea->title)
            ->line(new HtmlString("<a href=\"$link\">$link</a>"));
    }
}
