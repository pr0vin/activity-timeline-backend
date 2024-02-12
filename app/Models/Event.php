<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
