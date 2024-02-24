<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CreateIdea;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Idea extends Model
{
    use SoftDeletes;
    use Notifiable;

    protected $fillable = [
        'title',
        'email',
        'description',
        'category_id',
        'image'
    ];

    protected $appends = ['slug', 'pinned'];

    public static function boot() {
        parent::boot();

        static::creating(function($model)
        {
            // Image uploaded
            if ($model->image) {
                // base64 encoded image
                $base64 = $model->image;
                unset($model->image);

                // save image to storage
                list($baseType, $image) = explode(';', $base64);
                list(, $image) = explode(',', $image);
                $image = base64_decode($image);

                // get file extension
                if(!preg_match("/^data:image\/(jpeg|gif|png)/i",$baseType, $match)) {
                    throw new \Exception('Invalide mime type');
                }

                $type = $match[1];

                $fileName = Str::uuid() . ".$type";

                // Save file to disk
                Storage::disk('public')->put($fileName, $image);

                // Set image attribute to image url
                $model->image = Storage::disk('public')->url($fileName);
            }
        });
    }

    public function getSlugAttribute() {
        return Str::slug($this->title);
    }

    public function getPinnedAttribute() {
        return $this->category_id === Category::PINNED;
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class );
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail() {
        return $this->product->workspace->user->email;
    }

    public function sendCreateNotification() {
        $this->notify(new CreateIdea());
    }
}
