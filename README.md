# Soccer Manager API

A RESTful API for a fantasy football application where users create teams, manage players, and trade on a transfer marketplace.

## Tech Stack

- **Framework:** Laravel 13 (PHP 8.3)
- **Authentication:** Laravel Sanctum (token-based)
- **Database:** MySQL
- **Testing:** Pest

---

## Setup

### 1. Install dependencies

```bash
composer install
```

### 2. Environment

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database credentials in `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=soccer_manager
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Migrate and seed

```bash
php artisan migrate --seed
```

### 4. Run the application

```bash
php artisan serve
```

---

## API Reference

All authenticated endpoints require the header:

```
Authorization: Bearer {token}
```

### Authentication

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/api/register` | No | Register a new user |
| POST | `/api/login` | No | Login and receive a token |
| POST | `/api/logout` | Yes | Invalidate current token |

**Register**
```json
{
  "full_name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Login**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

---

### Team

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/team` | Yes | View your team and players |
| PUT | `/api/team` | Yes | Update team name or country |

**Update team**
```json
{
  "name": "My Team",
  "country_id": 1
}
```

On registration, a team of 20 players is automatically generated (3 GK, 6 DEF, 6 MID, 5 ATT) each valued at $1,000,000. The team starts with a $5,000,000 budget.

---

### Players

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| PUT | `/api/player/{id}` | Yes | Update player info (own team only) |
| POST | `/api/player/{id}/marketplace` | Yes | List player on transfer market |

**Update player**
```json
{
  "first_name": "Luka",
  "last_name": "Modric",
  "country_id": 1
}
```

**List on marketplace**
```json
{
  "price": "2500000.00"
}
```

---

### Marketplace

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/api/marketplace` | Yes | View all listed players (paginated) |
| POST | `/api/marketplace/{id}/buy` | Yes | Buy a listed player |

When a player is bought:
- The player moves to the buyer's team
- The asking price is deducted from the buyer's budget and added to the seller's budget
- The player's market value increases by a random 10–100%

---

## Running Tests

### Prepare the test database (run once, or after schema changes)

Create a MySQL database for testing and configure `.env.testing`, then:

```bash
composer test-prepare
```

### Run the test suite

```bash
composer test
```

36 feature tests covering authentication, team management, player updates, and marketplace transfers.
