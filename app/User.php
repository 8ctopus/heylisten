<?php

namespace App;

use App\Notifications\UserGreeting;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'job'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'photo_url', 'notifications'
    ];

    /**
     * Get the profile photo URL attribute.
     *
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower($this->email)).'.jpg?s=200&d=mm';
    }

    /**
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(OAuthProvider::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function ideas() {
        return $this->hasMany(Idea::class);
    }

    public function workspace() {
        return $this->hasOne(Workspace::class);
    }

    public function hasAdminAccess($item) {
        if ($item instanceof Comment) {
            return $this->id === $item->idea->product->workspace->user_id;
        }

        if ($item instanceof Idea) {
            return $this->id === $item->product->workspace->user_id;
        }

        if ($item instanceof Product) {
            return $this->id === $item->workspace->user_id;
        }

        if ($item instanceof Workspace) {
            return $this->id === $item->user_id;
        }

        throw new \Exception("Unknown item type");
    }

    public function sendGreetingNotification() {
        $this->notify(new UserGreeting());
    }

    public function setNotificationAsRead(string $id) {
        return $this->unreadNotifications()
            ->where('id', $id)
            ->update(['read_at' => now()]);
    }

    public function getNotificationsAttribute() {
        return $this->unreadNotifications;
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return config('services.slack.channel');
    }
}
