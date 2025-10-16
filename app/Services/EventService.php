<?php

namespace App\Services;

use App\Models\Event;

class EventService
{
    public function createEvent(array $data): Event
    {
        return Event::create($data);
    }

    public function listUpcomingEvents()
    {
        return Event::where('start_time', '>', now())->get();
    }
}
