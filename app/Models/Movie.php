<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'director',
        'writers',
        'stars',
        'poster',
        'release_date',
        'duration',
        'url_720',
        'url_1080',
        'url_4k',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
