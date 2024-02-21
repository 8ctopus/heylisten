<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'description'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function idea() {
        return $this->belongsTo(Idea::class);
    }

    protected $appends = [
        'is_admin',
    ];

    public function getIsAdminAttribute() {
        return $this->user_id === $this->idea->product->workspace->user_id;
    }
}
