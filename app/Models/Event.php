<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Event extends Model
{
    use HasFactory, Userstamps;

    protected $guarded = ['id'];


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function fiscalYear()
    {
        return $this->belongsTo(FiscalYear::class);
    }
}
