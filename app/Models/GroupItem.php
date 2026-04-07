<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupItem extends Model
{

    protected $fillable = [
        'group_id',
        'type',
        'title',
        'link',
        'date',
        'content',
        'file_path',
        'sort'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class, 'file_id');
    }
}
