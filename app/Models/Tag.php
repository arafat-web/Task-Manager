<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
    ];

    public function tasks()
    {
        return $this->morphedByMany(Task::class, 'taggable');
    }

    public function notes()
    {
        return $this->morphedByMany(Note::class, 'taggable');
    }
}
