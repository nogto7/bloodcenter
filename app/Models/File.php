<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $fillable = [
        'title','path','mime_type','size','folder_id', 'menu_id'
    ];

    protected static function booted()
    {
        static::deleting(function ($file) {
            if ($file->path && Storage::exists($file->path)) {
                Storage::delete($file->path);
            }
        });
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}

