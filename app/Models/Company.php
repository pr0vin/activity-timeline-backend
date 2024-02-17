<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Company extends Model
{
    use HasFactory, Userstamps;

    protected $guarded = ['id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
