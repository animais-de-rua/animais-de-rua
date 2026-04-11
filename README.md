<p align="center"><img src="https://animaisderua.org/img/logo/logo-text.svg" width="460"></p>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>


## About

This project uses the Laravel framework. 
This is the open source project of the website and intranet of Animais de Rua, an association who cares for the abandoned animals.
It's a work in progress for the website [Animais de Rua](https://animaisderua.org/).
It includes a backoffice for the organization and for the main website.


## Running Locally

**Requirements:** [Docker](https://docs.docker.com/get-docker/) and [Docker Compose](https://docs.docker.com/compose/install/)

**1. Start the services**

```bash
docker compose up --build
```

This builds the app image and starts both the app (PHP/Apache) and a MySQL database. The first build takes a few minutes. On subsequent runs, `--build` can be omitted.

**2. Open the app**

- Website: http://localhost:8080
- Admin panel: http://localhost:8080/admin/login
  - Email: `promatik@gmail.com`
  - Password: `password`

The database is seeded automatically on first startup.

**Test data**

To populate the database with additonal test data (processes, treatments, adoptions, vets, godfathers, store orders, and more), run:

```bash
./docker/load-test-data.sh
```


**Stopping**

```bash
docker compose down
```

Data is persisted in a Docker volume (`mysql_data`). To wipe the database as well:

```bash
docker compose down -v
```

---

## Contributing

Thank you for considering contributing to this platform!
Feel free to open issues and pull requests and check out our [Contributors Guide](./CONTRIBUTING.md) for help getting started.

## Security Vulnerabilities

If you discover a security vulnerability within this platform, please send an e-mail to António Almeida [promatik@gmail.com](mailto:promatik@gmail.com). All security vulnerabilities will be promptly addressed.


## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
