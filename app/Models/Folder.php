<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = ['name', 'parent_id', 'sort'];

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id')
            ->orderBy('sort')
            ->with('children');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    protected static function booted()
    {
        static::deleting(function ($folder) {
            $folder->children->each(function ($child) {
                $child->delete();
            });

            $folder->files()->delete();
        });
    }
}

