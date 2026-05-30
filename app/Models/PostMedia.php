<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $post_id
 * @property string $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Post $post
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostMedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostMedia whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostMedia wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostMedia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[Fillable(['post_id', 'image'])]
class PostMedia extends Model
{
    public function post(): BelongsTo {
        return $this->belongsTo(Post::class);
    }

    public function getImageUrlAttribute(): string {
        return asset('storage/' . $this->image);
    }
}
