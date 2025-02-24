<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class User extends Model implements Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['username','email','password'];

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Get the "remember me" token value.
     *
     * @return string|null
     */
    public function getRememberToken()
    {
        return $this->{$this->getRememberTokenName()};
    }

    /**
     * Set the "remember me" token value.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getAuthPasswordName()
    {
        return 'password';
    }

    public function posts()
    {
        return $this->hasMany(Posts::class);
    }

    /**
     * Get the users that this user follows
     */
    public function follows()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'followed_user_id');
    }

    public function isFollowing($userId)
    {
        return $this->follows()->where('followed_user_id', $userId)->exists();
    }

    /**
     * Get the users that follow this user
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_user_id', 'user_id');
    }

    public function getFollowerCount(): int
    {
        return $this->followers()->count();
    }


    public function notifications()
    {
        return $this->belongsToMany(Notifications::class, 'user_notifications', 'user_id', 'notification_id')
            ->withPivot('read_status')
            ->withTimestamps();
    }

    public function unreadNotifications()
    {
        return $this->notifications()->wherePivot('read_status', 'unread');
    }


    protected $casts = [
        'sent_at' => 'datetime',
        'read_at' => 'datetime'
    ];

    public function savedPosts()
    {
        return $this->belongsToMany(Posts::class, 'post_saves', 'user_id', 'post_id');
    }
}
