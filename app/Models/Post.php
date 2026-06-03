<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property int|null $root_id
 * @property int|null $repost_of_id
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $likedBy
 * @property-read Post|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PostMedia> $postMedia
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Post> $replies
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Post> $repost
 * @property-read Post|null $repost_of
 * @property-read Post|null $root
 * @property int $user_id
 * @property int $like_count
 * @property-read int|null $repost_count
 * @property-read int|null $replies_count
 * @property-read int $likes_count
 * @property-read int $reposts_count
 * @property-read int|null $liked_by_count
 * @property-read int|null $post_media_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereLikeCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereRepliesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereRepostCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereRepostOfId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereRootId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereUserId($value)
 * @mixin \Eloquent
 */
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

    public function repost(): HasMany {
        return $this->hasMany(Post::class, 'repost_of_id');
    }

    public function postMedia(): HasMany {
        return $this->hasMany(PostMedia::class);
    }

    // FIX: renamed dari isLikedBy() ke likedBy() agar konsisten dengan $this->likedBy()->...
    public function likedBy(): BelongsToMany {
        return $this->belongsToMany(User::class, 'likes', 'post_id', 'user_id');
    }

    public function checkIsReply(): bool {
        return !is_null($this->parent_id);
    }

    public function isLiked(User $user): bool {
        if (!$user) return false;
        return $this->likedBy()->where('user_id', $user->id)->exists();
    }

    public function hasMedia(): bool {
        return $this->postMedia()->exists();
    }

    public function canAddMedia(): bool {
        return $this->postMedia()->count() < 4;
    }

    public function getLikesCountAttribute(): int {
        return $this->likedBy()->count();
    }

    public function getRepliesCountAttribute(): int {
        return $this->replies()->count();
    }

    public function getRepostsCountAttribute(): int {
        return $this->repost()->count();
    }
}
