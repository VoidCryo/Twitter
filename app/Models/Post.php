<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['parent_id', 'root_id', 'repost_of_id', 'content', 'like_count', 'repost_count', 'replies_count'])]
class Post extends Model
{
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

    public function repost(): HasMany {
        return $this->hasMany(Post::class, 'repost_of_id');
    }

    public function postMedia(): HasMany {
        return $this->hasMany(PostMedia::class);
    }

    public function likedBy(): BelongsToMany {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id');
    }
}
