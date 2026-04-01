<?php

namespace App\Models;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'highlight_image',
        'is_active',
        'highlight',
        'publish_at',
        'user_id',
        'department_id', // нэмлээ
        'status'        // нэмлээ
    ];
    
    protected $casts = [
        'publish_at' => 'datetime',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    // public function ($query)
    // {
    //     return $query->where('status', 'published')
    //                 ->where('publish_at', '<=', now());
    // }
}
