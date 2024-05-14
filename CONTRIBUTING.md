# Contributing to Animais de Rua

### Prerequisites

```shell
laravel: "^6.0"
php: "^7.3"
nodejs: ">=20.11.0"
```

### Install

```bash
> npm install
> composer install
```

### Setup

You will need to have a local database already setup to run this project.
For example, if you have SQLite installed:

1. Create database file
   ``` bash
   # UNIX
   touch database/database.sqlite
   # Windows
   # :wq to exit
   vim database/database.sqlite
   ```
2. Update your `.env` file, removing every entry starting with "DB" except for:
   ```
   DB_CONNECTION=sqlite
   ```
3. Run database migrations
   ```bash
   php artisan migrate
   ```
4. Seed the database
   ```bash
   php artisan db:seed
   ```

### Development

To compile and watch changes in frontend

```shell
npm run watch
```

To build and serve the backend (in a different session or tab)

```shell
php artisan serve
```

You should now be able to access the website in http://localhost:8000/

Congratulations :)
