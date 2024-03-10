<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Wildside\Userstamps\Userstamps;

class Task extends Model
{
    use HasFactory, Userstamps;

    protected $guarded = ['id'];

    public function documentUrl(): ?string
    {
        return $this->documents ?
            Storage::disk('s3')->temporaryUrl($this->documents, now()->addMinutes(15))
            : null;
    }

    public function event(): BelongsTo
    {
        return  $this->belongsTo(Event::class);
    }
}
