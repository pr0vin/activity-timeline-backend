<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class FiscalYear extends Model
{
    use HasFactory, Userstamps;

    protected $guarded = ['id'];


    public function events()
    {
        $this->hasMany(Event::class);
    }
}
