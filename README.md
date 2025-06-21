# GemaDigital Laravel Boilerplate

<a href="https://packagist.org/packages/gemadigital/framework" title="Latest Version on Packagist"><img src="https://img.shields.io/packagist/v/gemadigital/framework.svg?style=flat-square"></a>
<a href="https://packagist.org/packages/gemadigital/framework" title="Total Downloads"><img src="https://img.shields.io/packagist/dt/gemadigital/framework.svg?style=flat-square"></a>
<a href="https://github.com/the-whole-fruit/manifesto"><img src="https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen" title="We believe writing good code is not only about writing good code. It’s also about the words around it. We aims to deliver both: code and words."></a>

GemaDigital Laravel Boilerplate

## Setup

1. Setup

```bash
composer run setup
```

2. Serve

```bash
composer run dev
```

---
# Running with docker

## Build the docker image

1) Build PHP image with MySQL
```bash
sudo docker build -t php:8.3.16-fpm-dev -f .docker/php/Dockerfile .
```
2) Initiate the containers
```bash
docker compose up -d
```

3) Enable external access to the database
```bash
docker exec -it animais-de-rua-upgrade-animais-de-rua.db-1 mysql -u root -p
```
Users password is `root`
Then execute the command:
```sql
ALTER USER 'root'@'%' IDENTIFIED WITH mysql_native_password BY 'root';
FLUSH PRIVILEGES;
```
Then exit the container with `exit`

If the container name doesn't match run:
```bash
docker ps | grep db
```

## Using the binaries on the .bin for local development
### Make sure you have `direnv` installed

`direnv` is a tool that automatically loads environment variables when you enter a directory. Follow the instructions below to install `direnv` on different systems.

[Install `direnv`](https://direnv.net/docs/installation.html)

Then run the following command to allow the `.envrc` file to be loaded automatically when you enter the project directory.

```bash
direnv allow
```

Then you can follow the setup steps as normal.

## Usefull commands

-   Packages a ready for production zip

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
