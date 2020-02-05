<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function ideas() {
        return $this->hasMany(Idea::class);
    }

    public function workspace() {
        return $this->belongsTo(Workspace::class);
    }
}
