# Casio Calculator

A scientific calculator web application built with **Laravel 12** and **Vue 3**, styled with **Tailwind CSS 4**.

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 20
- npm
- SQLite

## Installation

```bash
# Clone the repository
git clone https://github.com/harlequindoyon/casio-calc.git
cd casio-calc

# Run the setup script (installs dependencies, copies .env, generates key, runs migrations, builds assets)
composer setup
```

Or manually:

```bash
# Install PHP dependencies
composer install

# Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# Create the SQLite database and run migrations
touch database/database.sqlite
php artisan migrate

# Install Node dependencies and build frontend assets
npm install
npm run build
```

## Running the Application

### Development (all services at once)

```bash
composer dev
```

This starts the following services concurrently:

| Service         | Description               |
| --------------- | ------------------------- |
| **server**      | `php artisan serve`       |
| **queue**       | `php artisan queue:listen` |
| **logs**        | `php artisan pail`        |
| **vite**        | `npm run dev`             |

The application will be available at **http://localhost:8000**.

### Production build

```bash
npm run build
php artisan serve
```

## Running Tests

```bash
composer test
```

Or directly:

```bash
php artisan test
```

## API Documentation

API docs are generated with [Scribe](https://scribe.knuckleswtf.com).

```bash
php artisan scribe:generate
```

Once generated, the docs are available at **http://localhost:8000/docs**.

## API Overview

All API endpoints require an `X-Session-Id` header (UUID v4) to identify the browser session.

| Method   | Endpoint                        | Description                |
| -------- | ------------------------------- | -------------------------- |
| `GET`    | `/api/calculations`             | List session calculations  |
| `POST`   | `/api/calculations`             | Evaluate an expression     |
| `DELETE`  | `/api/calculations/{uuid}`     | Delete a calculation       |
| `DELETE`  | `/api/calculations`             | Clear all calculations     |

## Tech Stack

- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: Vue 3, Tailwind CSS 4, Vite 7
- **Database**: SQLite
- **Math Parser**: [mossadal/math-parser](https://github.com/mossadal/math-parser)
- **API Docs**: Scribe
- **CI/CD**: GitHub Actions (Pint, PHPUnit, Scribe)

## License

[MIT](https://opensource.org/licenses/MIT)
