<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'feedback_type',
        'message',
        'feedback_position',
    ];
}
