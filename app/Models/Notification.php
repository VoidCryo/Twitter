<?php

namespace App\Models;

use App\Enum\NotificationType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $user_id
 * @property int|null $post_id
 * @property int|null $actor_id
 * @property NotificationType $type
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Models\User|null $actor
 * @property-read \App\Models\Post|null $post
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereActorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUserId($value)
 * @mixin \Eloquent
 */
#[Fillable(['user_id', 'post_id', 'actor_id', 'type', 'read_at'])]
class Notification extends Model
{
    protected function casts(): array
    {
        return [
            'type' => NotificationType::class,
            'read_at' => 'datetime',
            'created_at' => 'datetime'
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo {
        return $this->belongsTo(Post::class);
    }

    public function actor(): BelongsTo {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
