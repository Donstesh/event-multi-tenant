
# Event Multi-Tenant Management System

Build a simplified multi-tenant event management system with Laravel backend and Nuxt.js frontend.

## Repository
[https://github.com/Donstesh/event-multi-tenant.git](https://github.com/Donstesh/event-multi-tenant.git)

---

## Requirements

- PHP >= 8.0
- Composer
- MySQL or any database supported by Laravel
- Node.js & npm (for Nuxt.js frontend and optionally for Laravel frontend tooling)
- Laravel 12.x (or your Laravel version)

---

## Backend Installation (Laravel)

### 1. Clone the repository

```bash
git clone https://github.com/Donstesh/event-multi-tenant.git
cd event-multi-tenant
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Copy `.env` and configure

```bash
cp .env.example .env
```

Edit `.env` to add your database credentials and other environment variables.

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Run database migrations

```bash
php artisan migrate
```

### 6. (Optional) Install Node dependencies and build assets

If you want to use frontend assets built with Laravel Mix or Vite:

```bash
npm install
npm run dev
```

---

## Frontend Installation (Nuxt.js)

Navigate to your Nuxt.js frontend folder (assumed to be `frontend`):

```bash
cd public-event
npm install
npm run dev
```

Open your browser at `http://localhost:3000` to view the frontend app.

---

## Running the Laravel Backend

Start the Laravel development server:

```bash
php artisan serve
```

By default, the backend runs at `http://localhost:8000`.

---

## API Documentation (Swagger)

### 1. Place your Swagger YAML file

Put your `swagger.yaml` in:

```
storage/app/api-docs/swagger.yaml
```

### 2. Access Swagger UI

Open your browser at:

```
http://localhost:8000/api-docs
```

This will load the Swagger UI with your API documentation.

---

## Notes

- Configure API authentication tokens or middleware as needed.
- You can protect the Swagger UI route by adding middleware in `routes/web.php`.
- Ensure correct permissions to read `swagger.yaml` file.

---

## Troubleshooting

- Verify `swagger.yaml` exists at `storage/app/api-docs`.
- Confirm the `/api-docs/swagger.yaml` route serves the YAML file with correct content type.
- Check Laravel logs for any errors.

---

## Contact

For questions or support, contact [Your Name or Team] at [stephenshifoko@gmail.com].
