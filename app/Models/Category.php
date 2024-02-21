<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const PINNED = 4;

    use Translatable;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'color',
        'icon'
    ];

    public $translatable = ['title'];
    public $casts = ['title' => 'json'];

    public function ideas() {
        return $this->belongsToMany(Idea::class);
    }
}
