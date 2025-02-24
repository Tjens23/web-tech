<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'sent_at',
        'notifiable_type',
        'notifiable_id',
        'notification_by',
        'read_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }
}
