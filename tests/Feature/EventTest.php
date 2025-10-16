<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Event;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_event()
    {
        $response = $this->postJson('/api/events', [
            'name' => 'Sample Event',
            'location' => 'Sample Location',
            'start_time' => now()->addDay()->toDateTimeString(),
            'end_time' => now()->addDays(2)->toDateTimeString(),
            'max_capacity' => 100,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('events', ['name' => 'Sample Event']);
    }

    public function test_list_events()
    {
        Event::factory()->create(['start_time' => now()->addDay(), 'end_time' => now()->addDays(2)]);

        $response = $this->getJson('/api/events');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }
}
