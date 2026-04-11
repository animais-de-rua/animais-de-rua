# CLAUDE.md

## Project overview

Laravel 5.x app for [Animais de Rua](https://animaisderua.org/), a Portuguese animal welfare association. It serves both the public website and an admin intranet (Backpack/AdminLTE). Run exclusively via Docker for local development.

## Running locally

```bash
docker compose up --build   # first time; omit --build on subsequent runs
docker compose down         # stop (data persisted in mysql_data volume)
docker compose down -v      # stop and wipe database
./docker/load-test-data.sh  # optional: seed extra test data
```

- Website: http://localhost:8080
- Admin: http://localhost:8080/admin/login — `promatik@gmail.com` / `password`

## Frontend asset pipeline

Assets are compiled with **laravel-mix 6 / webpack 5** (`npm run prod`).

There are **two separate icomoon icon sets** — do not confuse them:

| Set | Location | Purpose | How it reaches the browser |
|-----|----------|---------|---------------------------|
| Website icons (cart, donate…) | `resources/assets/sass/icomoon/` | Compiled into `public/css/app.css` via SCSS `@import` | Font files emitted by webpack to `public/fonts/` |
| Admin icons (process, appointment…) | `public/css/admin/icomoon/` | Committed static asset, loaded via `<link>` in the Backpack layout | Served directly from the repo via `COPY . .` in Docker |

The admin icomoon must **not** be copied through webpack — it is a different icon set with different glyphs.

## Docker architecture

**Multi-stage build** (`Dockerfile`):
1. `frontend` stage — Node 20, runs `npm run prod`, outputs compiled assets under `/app/public/`
2. `stage-1` — PHP 7.4 + Apache, copies app source, installs Composer deps, runs artisan commands at build time

Key decisions and their reasons:

- **CSS is copied file-by-file** (`app.css`, `admin/reports.css`), not as a directory. A directory `COPY --from=frontend /app/public/css ./public/css` creates an opaque overlay layer that silently wipes `public/css/admin/icomoon/` (a committed static asset). File-by-file COPYs avoid this.
- **`public/fonts/`** comes from `COPY --from=frontend /app/public/fonts ./public/fonts`. Webpack 5 asset modules automatically emit font files referenced in CSS here — no `copyDirectory` in `webpack.mix.js` needed.
- **`.env.example` → `.env`** is copied at build time (`RUN cp .env.example .env`) so artisan commands (`key:generate`, `vendor:publish`) have a config file. At runtime, `docker-compose.yml` loads the real `.env` via `env_file` and overrides DB settings via `environment`.
- **`mix.setPublicPath('./public')`** is injected into `webpack.mix.js` via `sed` in the Dockerfile because laravel-mix v6 resolves versioned asset paths from the filesystem root otherwise.

## `.dockerignore` rationale

- `public/js/` — fully compiled, no static assets
- `public/css/app.css` and `public/css/admin/reports.css` — compiled; copied from frontend stage instead
- `public/css/admin/icomoon/` is intentionally **not** excluded — it must reach the runtime image via `COPY . .`

## Docker scripts

All Docker-related scripts live in `docker/`:
- `entrypoint.sh` — runs migrations/seeding on first startup
- `load-test-data.sh` + `test-data.sql` — loads additional test data into the running Docker Compose database
