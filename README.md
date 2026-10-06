# Project Selector

A web application where teacher can submit project and keep track of students taken project and students can take and complete projects.

## Technology:

- HTML5
- CSS3
- PHP

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
