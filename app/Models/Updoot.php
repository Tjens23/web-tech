<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Updoot extends Model
{
    protected $fillable = ['user_id', 'post_id', 'value'];
}
