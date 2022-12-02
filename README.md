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

1. Install PHP extensions

```
sudo apt-get install php-xml
sudo apt-get install php-curl
sudo apt-get install php8.1-pdo-mysql // (or whichever php-mysql extension applies to your verision)
```

2. Install the PHP dependencies with:
```
composer install
```
3. Create a database

```
sudo mysql -u root
```

then

```
mysql> CREATE DATABASE DB_NAME;
mysql> CREATE USER 'DB_USER_NAME'@localhost IDENTIFIED BY 'DB_USER_PASSWORD';
mysql> GRANT ALL PRIVILEGES ON DB_NAME.* TO 'DB_USER_NAME'@localhost;
mysql> FLUSH PRIVILEGES;
mysql> exit;
```

4. Copy `.env.example` to `.env`, and update the following values in `.env`:
  - `DB_CONNECTION`
  - `DB_HOST`
  - `DB_PORT`
  - `DB_DATABASE` (DB_NAME)
  - `DB_USERNAME` (DB_USER_NAME)
  - `DB_PASSWORD` (DB_USER_PASSWORD)
5. Generate the database application key with:
```
php artisan key:generate
```
6. Create the required database tables and generate their base contents with:
```
php artisan migrate:fresh --seed
```
7. Install the JavaScript dependencies with:
```
npm install --legacy-peer-deps
```

## Run a local instance
Host the JS assets with:
```
npm run dev
```
And then, while thats running, in a different terminal start the php server with:
```
php artisan serve
```

Your local instance of Utgar's Chronicles should be available from http://127.0.0.1:8000/
