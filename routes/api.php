<?php
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendeeController;

Route::post('/events', [EventController::class, 'create']);
Route::get('/events', [EventController::class, 'list']);
Route::get('/events/{event_id}/attendees', [AttendeeController::class, 'list']);
Route::post('/events/{event_id}/register', [AttendeeController::class, 'register']);

// Updated Swagger documentation route
Route::get('/swagger', function () {
    return view('vendor.l5-swagger.index');
});
?>
