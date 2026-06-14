<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'parent_id', 'root_id', 'repost_of_id', 'content'])]
class Post extends Model
{
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    public function root(): BelongsTo {
        return $this->belongsTo(Post::class, 'root_id');
    }

    public function repost_of(): BelongsTo {
        return $this->belongsTo(Post::class, 'repost_of_id');
    }

    public function replies(): HasMany {
        return $this->hasMany(Post::class, 'parent_id');
    }

    public function reposts(): HasMany {
        return $this->hasMany(Post::class, 'repost_of_id');
    }

    public function postMedia(): HasMany {
        return $this->hasMany(PostMedia::class);
    }

    public function likedBy(): BelongsToMany {
        return $this->belongsToMany(User::class, 'likes', 'post_id', 'user_id');
    }

    public function checkIsReply(): bool {
        return !is_null($this->parent_id);
    }

    public function isLiked(?User $user): bool {
        if (!$user) return false;
        return $this->likedBy()->where('user_id', $user->id)->exists();
    }

    public function hasMedia(): bool {
        return $this->postMedia()->exists();
    }

    public function canAddMedia(): bool {
        return $this->postMedia()->count() < 4;
    }

}
