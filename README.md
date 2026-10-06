# Project Selector

A web application where teacher can submit project and keep track of students taken project and students can take and complete projects.

## Technology

- PHP 8.3 (plain PHP, PDO) on Apache
- MariaDB 11
- HTML and a single hand-written stylesheet (`styles/app.css`) with light and dark themes
- Docker / Docker Compose

## Structure

- `*.php` in the root: pages (`projects.php`, `profile.php`, ...) and the form actions they post to
- `includes/bootstrap.php`: database connection, session, CSRF and escaping helpers, role checks
- `includes/header.php`, `includes/footer.php`: shared layout, navigation and toast messages
- `db/init.sql`: schema and demo data

## Running with Docker

Requires Docker with Compose (or Podman with `podman-compose`).

```bash
cp .env.example .env   # optional, defaults work for local dev
docker compose up --build
```

- App: http://localhost:8080
- Adminer (DB browser, dev only): http://localhost:8081 — server `db`, user/password from `.env`
- PHP files are mounted into the container, so edits show up on refresh.

The database is created from `db/init.sql` on first start. Demo accounts (password `Password1`):

| Role    | E-mail              |
| ------- | ------------------- |
| Admin   | admin@example.com   |
| Teacher | teacher@example.com |
| Student | student@example.com |

To reset the database: `docker compose down -v`.

### Deployment

`docker-compose.override.yml` holds dev-only extras (source mount, exposed DB port, Adminer). Deploy with the base file only, after setting real passwords and `APP_DEBUG=0` in `.env`:

```bash
docker compose -f docker-compose.yml up -d --build
```
