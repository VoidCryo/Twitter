<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
