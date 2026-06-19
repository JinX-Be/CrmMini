# Mini CRM

A lightweight, multi-niche CRM for service businesses. Window cleaning is the
active niche; the engine is niche-agnostic (see `config/niche.php`).

See [PLAN.md](PLAN.md) for the architecture and roadmap.

## Requirements

- PHP **8.2+** (with `mbstring`, `xml`, `curl`, `sqlite3` extensions)
- [Composer](https://getcomposer.org/)

> No Node/npm build step is needed — styling uses the Tailwind CDN in Phase 0.

## First-time setup

```bash
# 1. Install PHP dependencies (creates vendor/)
composer install

# 2. Create your .env and an app key
cp .env.example .env
php artisan key:generate

# 3. Create the SQLite database file (already present, but to be safe)
touch database/database.sqlite

# 4. Run migrations and seed the demo owner account
php artisan migrate --seed

# 5. Start the dev server
php artisan serve
```

Then open http://localhost:8000.

## Demo login

| Email               | Password   | Role  |
|---------------------|------------|-------|
| `owner@example.com` | `password` | owner |

## Switching niche

Set `NICHE` in `.env` to one of the keys defined in `config/niche.php`:

```dotenv
NICHE=window_cleaning   # default
# NICHE=solar_cleaning
# NICHE=dog_grooming
```

The layout, theme colour, terminology, and (from Phase 1) the customer custom
fields all follow the active niche. In Phase 5 the niche will instead be
resolved from the request hostname — only `App\Http\Middleware\ResolveNiche`
changes; everything that consumes the niche stays the same.

## Project layout (Phase 0)

```
app/
  Http/Controllers/      Auth + dashboard controllers
  Http/Middleware/       ResolveNiche (active-niche resolution)
  Models/User.php        Owner/staff accounts
  Support/Niche.php      The resolved-niche object the app depends on
config/niche.php         Niche definitions (fields, theme, terms)
database/migrations/     Users, cache, jobs tables
database/seeders/        Demo owner account
resources/views/         Niche-aware Blade layout, login, dashboard
routes/web.php           Login + dashboard routes
```

## What's next

- **Phase 1** — Customers with niche custom fields
- **Phase 2** — Appointments + calendar view
- **Phase 3** — Invoices from completed appointments
- **Phase 4** — Reschedule reminders (email + SMS) via scheduled jobs
- **Phase 5** — Resolve niche by URL / subdomain
- **Phase 6** — JSON API for a dedicated app
