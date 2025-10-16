<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'start_time',
        'end_time',
        'max_capacity',
    ];

    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }
}