# E-Commerce API + Filament Dashboard Setup

## Tech Stack

* Laravel 11
* Filament v3
* Laravel Sanctum
* MySQL
* Pest Testing

---

# 1. Create Laravel Project

```bash
composer create-project laravel/laravel ecommerce-api "11.*"
```

Go to project folder:

```bash
cd ecommerce-api
```

---

# 2. Configure Environment

Open `.env`

Update database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_api
DB_USERNAME=root
DB_PASSWORD=
```

---

# 3. Run Initial Migration

```bash
php artisan migrate
```

---

# 4. Install Filament

Install package:

```bash
composer require filament/filament:"^3.2"
```

Install Filament panel:

```bash
php artisan filament:install --panels
```

Create admin user:

```bash
php artisan make:filament-user
```

Access admin panel:

```txt
http://127.0.0.1:8000/admin
```

---

# 5. Install Laravel Sanctum

Install package:

```bash
composer require laravel/sanctum
```

Publish Sanctum config:

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

Run migration:

```bash
php artisan migrate
```

---

# 6. Configure Sanctum

Open:

```txt
app/Models/User.php
```

Add import:

```php
use Laravel\Sanctum\HasApiTokens;
```

Update class:

```php
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
}
```

---

# 7. Enable API Routing

Create file:

```txt
routes/api.php
```

Add test route:

```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'message' => 'API working'
    ]);
});
```

---

# 8. Register API Routes

Open:

```txt
bootstrap/app.php
```

Find:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
```

Replace with:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
```

---

# 9. Configure API Middleware

Inside `bootstrap/app.php`

Find:

```php
->withMiddleware(function (Middleware $middleware) {
    //
})
```

Replace with:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->statefulApi();
})
```

---

# 10. Run Development Server

```bash
php artisan serve
```

Test API:

```txt
http://127.0.0.1:8000/api/test
```

---

# 11. Install Spatie Permission

Install package:

```bash
composer require spatie/laravel-permission
```

Publish config:

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

Run migration:

```bash
php artisan migrate
```

---

# 12. Install Pest Testing

Install Pest:

```bash
composer require pestphp/pest --dev
```

Setup Pest:

```bash
php artisan pest:install
```

Run tests:

```bash
php artisan test
```

---

# 13. Create API Controller

Example:

```bash
php artisan make:controller API/ProductController --api
```

---

# 14. Create Filament Resource

Example Product Resource:

```bash
php artisan make:filament-resource Product
```

---

# Recommended Project Structure

```txt
app/
├── Actions/
├── Services/
├── Repositories/
├── Models/
├── Http/
│   ├── Controllers/API
│   ├── Requests
│   ├── Resources
```

---

# Recommended Database Tables

```txt
users
categories
products
product_images
carts
cart_items
orders
order_items
payments
reviews
wishlists
```

---

# Recommended API Versioning

Example:

```php
Route::prefix('v1')->group(function () {

    Route::post('/login', ...);

    Route::get('/products', ...);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', ...);

        Route::apiResource('orders', OrderController::class);
    });
});
```

---

# Useful Commands

## Route List

```bash
php artisan route:list
```

## Create Model + Migration

```bash
php artisan make:model Product -m
```

## Create Seeder

```bash
php artisan make:seeder ProductSeeder
```

## Run Seeder

```bash
php artisan db:seed
```

## Fresh Migration

```bash
php artisan migrate:fresh --seed
```

---

# MVP Features

* Authentication
* Categories
* Products
* Cart
* Orders
* Filament Dashboard
* Image Upload
* Basic Testing

---

# Future Features

* Wishlist
* Coupons
* Product Variants
* Reviews & Ratings
* Analytics Dashboard
* Payment Gateway
* Notifications

---

# Recommended Frontend Stack

* Next.js
* Tailwind CSS
* Shadcn UI
* Axios / React Query

---

# Final Stack

```txt
Laravel 11
+ Filament
+ Sanctum
+ MySQL
+ Pest
+ Next.js
+ Tailwind
+ Shadcn UI
```
