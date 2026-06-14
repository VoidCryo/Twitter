<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile(): HasOne {
        return $this->hasOne(Profile::class);
    }

    public function posts(): HasMany {
        return $this->hasMany(Post::class);
    }

    public function topLevelPosts(): HasMany {
        return $this->hasMany(Post::class)->whereNull('parent_id');
    }

    public function likedPosts(): BelongsToMany {
        return $this->belongsToMany(Post::class, 'likes', 'user_id', 'post_id')->withTimestamps();
    }

    public function followings(): BelongsToMany {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    }

    public function followers(): BelongsToMany {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    public function isFollowing(User $user): bool {
        return $this->followings()->where('following_id', $user->id)->exists();
    }

    public function toggleFollowing(User $user): void {
        $this->followings()->toggle($user->id);
    }

    public function isLikedPost(Post $post): bool {
        return $this->likedPosts()->where('post_id', $post->id)->exists();
    }

    public function toggleLiked(Post $post): void {
        $this->likedPosts()->toggle($post->id);
    }
}
