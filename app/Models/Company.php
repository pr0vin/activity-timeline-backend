<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Company extends Model
{
    use HasFactory, Userstamps;

    protected $guarded = ['id'];


    protected $dates = [
        'expiry_date', 'created_at'
    ];

    protected $appends = [
        'remaining_days',
    ];




    public function getRemainingDaysAttribute()
    {
        if ($this->expiry_date && $this->expiry_date > now()) {
            return now()->diffInDays($this->expiry_date);
        }

        return null;
    }


    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
