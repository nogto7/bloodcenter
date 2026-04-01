<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'title',
        'menu_id',
        'name',
        'description',
        'cover_image',
        'color',
        'is_active',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class)->orderBy('order');
    }
}
