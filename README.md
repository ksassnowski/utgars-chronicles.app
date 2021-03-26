# Utgar's Chronicles

Source code of https://utgars-chronicles.app, a free web app to help you play Microscope online.

## Installation

### Pre-requisites

* PHP ≥ 7.4
* Composer
* MariaDB (or another database engine supported by Laravel 8)
* Node.js
* npm (or another JavaScript packages manager supported by Laravel 8)

### Environment setup

1. Install the PHP dependencies with:
```
composer install
```
2. Create a database
3. Copy `.env.example` to `.env`, and update the following values in `.env`:
  - `DB_CONNECTION`
  - `DB_HOST`
  - `DB_PORT`
  - `DB_DATABASE`
  - `DB_USERNAME`
  - `DB_PASSWORD`
4. Generate the database application key with:
```
php artisan key:generate
```
5. Create the required database tables and generate their base contents with:
```
php artisan migrate:fresh --seed
```
6. Install the JavaScript dependencies with:
```
npm install --legacy-peer-deps
```
7. Build the JavaScript-generated assets with:
```
npm run development
```

## Run a local instance

```
php artisan serve
```

Your local instance of Utgar's Chronicles should be available from http://127.0.0.1:8000/
