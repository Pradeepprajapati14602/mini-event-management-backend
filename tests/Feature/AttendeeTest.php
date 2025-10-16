<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Attendee;

class AttendeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_attendee()
    {
        $event = Event::factory()->create(['max_capacity' => 1]);

        $response = $this->postJson("/api/events/{$event->id}/register", [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('attendees', ['email' => 'john.doe@example.com']);
    }

    public function test_attendee_list()
    {
        $event = Event::factory()->create();
        Attendee::factory()->create(['event_id' => $event->id]);

        $response = $this->getJson("/api/events/{$event->id}/attendees");

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }
}