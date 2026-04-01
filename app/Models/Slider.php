<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $table = 'slider';

    protected $fillable = [
        'title',
        'url',
        'desc',
        'content',
        'sort',
        'highlight_image',
        'active',
    ];
}
