<?php

namespace App\Services;

use App\Models\Attendee;
use App\Models\Event;

class AttendeeService
{
    public function registerAttendee(Event $event, array $data): Attendee
    {
        if ($event->attendees()->count() >= $event->max_capacity) {
            throw new \Exception('Event is fully booked');
        }

        $existingAttendee = $event->attendees()->where('email', $data['email'])->first();

        if ($existingAttendee) {
            throw new \Exception('Attendee already registered');
        }

        return $event->attendees()->create($data);
    }

    public function listAttendees(Event $event)
    {
        return $event->attendees;
    }
}