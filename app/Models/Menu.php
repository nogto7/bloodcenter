<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'url',
        'parent_id',
        'sort',
        'active',
        'type'
    ];

    public static function types()
    {
        return [
            'news'   => 'Мэдээ',
            'files'  => 'Файл',
            'page'   => 'Энгийн хуудас',
            'custom' => 'Тусгай layout',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')
        ->orderBy('sort');
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class)->orderBy('order');
    }
}

