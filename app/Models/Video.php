<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected $casts = [
        'processed'=>'bool'
    ];


    public function getRouteKeyName(): string
    {
        return 'uid';
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function getThumbnailAttribute(): string
    {
        if ($this->thumbnail_image) {
            return '/videos/' . $this->uid . '/' . $this->thumbnail_image;
        } else {
            return '/videos/' . 'default.png';
        }
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function dislikes() {
        return $this->hasMany(Dislike::class);
    }


    public function doesUserLikedVideo(): bool
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }
    public function doesUserDislikedVideo(): bool
    {
        return $this->dislikes()->where('user_id', auth()->id())->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)
            ->whereNull('comment_id');
    }

    public function AllCommentsCount()
    {
        return $this->hasMany(Comment::class)
            ->count();
    }
}
