<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserGreeting extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @param  User  $user
     * @return array
     */
    public function via(User $user)
    {
        $channles = ['mail', 'database'];
        if (config('services.slack.channel')) {
            array_push($channles, 'slack');
        }
        return $channles;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  User  $user
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(User $user)
    {
        return (new MailMessage)
            ->subject('Welcome to '.config('app.name'))
            ->greeting("Welcome aboard, {$user->name}!")
            ->line('You are one step closer to getting feedback from your clients.')
            ->line('Here\'s how to start with Hey,Listen!:')
            ->line('1. Create a project. Come up with a name and a short URL for your future feedback page.')
            ->line('2. Add sample ideas. Inspire your users by creating a few ideas. Nobody likes to go first.')
            ->line('3. Share a link. Copy your project link and send it to your customers via email or put a link on your website.')
            ->action('Get Started', url(config('app.client_url').'/login'));
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $user
     * @return SlackMessage
     */
    public function toSlack(User $user)
    {
        $name = $user->name;
        $email = $user->email;

        return (new SlackMessage)
            ->success()
            ->content("New user registration: $name [$email]");
    }

    /**
     * Get the array representation of notification
     *
     * @param User $user
     * @return array
     */
    public function toArray(User $user) {
        return [];
    }
}
