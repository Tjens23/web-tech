<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comments extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'comment'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Posts::class);
    }

    /**
     * Get the user that wrote the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
