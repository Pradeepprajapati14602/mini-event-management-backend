# Mini Event Management System Backend

Welcome to the backend of the Mini Event Management System, built with Laravel and PostgreSQL. This project is designed to manage events and attendees efficiently.

## Features

- **Event Management**: Create, list, and manage events.
- **Attendee Registration**: Register attendees for specific events.
- **Attendee Listing**: View attendees for a specific event with pagination.
- **API Documentation**: Swagger documentation for all endpoints.

## Getting Started

### Prerequisites

Ensure you have the following installed:

- PHP (v8.0 or higher)
- Composer
- PostgreSQL or SQLite

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/Pradeepprajapati14602/mini-event-management-backend.git
   ```

2. Navigate to the project directory:

   ```bash
   cd mini-event-management-backend
   ```

3. Install dependencies:

   ```bash
   composer install
   ```

4. Set up the environment file:

   ```bash
   cp .env.example .env
   ```

   Update the `.env` file with your database credentials.

5. Run migrations:

   ```bash
   php artisan migrate
   ```

6. Start the development server:

   ```bash
   php artisan serve
   ```

   Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

## API Endpoints

### Event Management

- **POST /api/events**: Create a new event.
- **GET /api/events**: List all upcoming events.

### Attendee Management

- **POST /api/events/{event_id}/register**: Register an attendee for an event.
- **GET /api/events/{event_id}/attendees**: List all attendees for an event.

## Project Structure

- `app/Http/Controllers`: Contains the controllers for handling API requests.
- `app/Models`: Contains the models for `Event` and `Attendee`.
- `routes/api.php`: Defines the API routes.
- `database/migrations`: Contains the database schema.

## Learn More

To learn more about Laravel, take a look at the following resources:

- [Laravel Documentation](https://laravel.com/docs) - Comprehensive documentation for Laravel.
- [Laracasts](https://laracasts.com) - Video tutorials for Laravel and PHP.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
