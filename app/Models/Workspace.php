<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    public const RESTRICTED_NAMES = [
        'setting', 'settings', 'login', 'register', 'password',
        'signup', 'logout', 'account', 'blog', 'privacy-policy',
        'privacy', 'policy'
    ];


    protected $fillable = [
        'alias',
    ];

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
