# GemaDigital Laravel Boilerplate

<a href="https://packagist.org/packages/gemadigital/framework" title="Latest Version on Packagist"><img src="https://img.shields.io/packagist/v/gemadigital/framework.svg?style=flat-square"></a>
<a href="https://packagist.org/packages/gemadigital/framework" title="Total Downloads"><img src="https://img.shields.io/packagist/dt/gemadigital/framework.svg?style=flat-square"></a>
<a href="https://github.com/the-whole-fruit/manifesto"><img src="https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen" title="We believe writing good code is not only about writing good code. It’s also about the words around it. We aims to deliver both: code and words."></a>

GemaDigital Laravel Boilerplate

## Setup

1) Install dependencies and copy the .env
```bash
composer install
npm install
```

2) Copy the .env file, generate a key and the assets
```bash
php -r "copy('.env.example', '.env');"
php artisan key:generate
npm run prod
```

3) Create a database and fill the .env file with those details
```bash
# .env
DB_DATABASE=laravel
DB_USERNAME=user
DB_PASSWORD=password
```

4) Run the migrations and create the admin user
```bash
php artisan migrate
php artisan db:seed
```

5) Serve the project
```bash
php artisan serve
```

---

## Usefull commands

- Packages a ready for production zip
```bash
php artisan boilerplate:package
```

- Create a new boilerplate project
```bash
php artisan boilerplate:duplicate
```

## Credits

- [António Almeida][link-author]

## License

Please see the [license file](license.md) for more information.

[link-author]: https://github.com/promatik
