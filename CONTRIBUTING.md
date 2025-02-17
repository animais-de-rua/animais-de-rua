# Contributing to Animais de Rua

### Prerequisites

```shell
laravel: "^6.0"
php: "^7.3"
nodejs: ">=20.11.0"
mysql: "^5.7"
```

### Install

```bash
> npm install
> composer install
```

### Setup

You will need to have a local database already setup to run this project. For MySQL:

1. Copy and rename the `.env.example` file to `.env`
   ```bash
   cp .env.example .env
   ```
2. Create `animaisderua` database
   ```bash
   mysql -u root -p
   CREATE DATABASE animaisderua;
   ```
3. Run database migrations
   ```bash
   php artisan migrate
   ```
3. Seed the database
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
