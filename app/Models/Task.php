<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Task extends Model
{
    use HasFactory, Userstamps;

    protected $guarded = ['id'];


    public function event()
    {
        return  $this->belongsTo(Event::class);
    }
}
