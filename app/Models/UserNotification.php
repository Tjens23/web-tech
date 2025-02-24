<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'notification_id', 'read_status'];

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notifications::class, 'notification_id'); // Correct column name
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
