# Deploying Mini CRM (Render + Neon, free tier)

This app deploys as a **Docker web service on Render** (free) with its database
on **Neon** (free Postgres). Neon is used for the DB because Render's own free
Postgres expires 30 days after creation — Neon's free tier does not expire.

Files that make this work (already in the repo):

- `Dockerfile` — PHP 8.3 + Apache, serves `public/`, installs Composer deps
- `docker/entrypoint.sh` — binds Apache to Render's `$PORT`, caches config, runs migrations
- `render.yaml` — Render Blueprint (service + env vars)
- `config/database.php` — Postgres via `DB_URL`

---

## 1. Create the database on Neon

1. Sign up at https://neon.tech and create a **project** (pick a region near your Render region, e.g. EU).
2. In the project, open **Connect** / **Connection Details**.
3. Copy the **connection string**. It looks like:

   ```
   postgresql://USER:PASSWORD@ep-xxxx-yyyy.eu-central-1.aws.neon.tech/DBNAME?sslmode=require
   ```

   Keep this — it's your `DB_URL`. (Use the **pooled** connection string if Neon offers one.)

## 2. Generate an app key

Locally (needs PHP/Composer; run `composer install` first if you haven't):

```bash
php artisan key:generate --show
```

Copy the output (it starts with `base64:`). That's your `APP_KEY`.

> If you can't run PHP locally, any 32-byte base64 value works:
> `echo "base64:$(openssl rand -base64 32)"`

## 3. Push the code to Git

Create a GitHub/GitLab repo and push:

```bash
git init
git add .
git commit -m "Mini CRM — Phase 0"
git branch -M main
git remote add origin <your-repo-url>
git push -u origin main
```

## 4. Create the Render service from the Blueprint

1. Sign up at https://render.com and connect your Git provider.
2. **New → Blueprint**, select the repo. Render reads `render.yaml` and proposes
   a free Docker web service named `mini-crm`.
3. Before/after the first deploy, set the three secret env vars (they're marked
   `sync: false`, so Render asks for them):

   | Variable  | Value                                                        |
   |-----------|--------------------------------------------------------------|
   | `APP_KEY` | the `base64:...` value from step 2                           |
   | `DB_URL`  | the Neon connection string from step 1                       |
   | `APP_URL` | your Render URL, e.g. `https://mini-crm.onrender.com`        |

   (`APP_URL` is shown on the service page once created. You can set it, then
   trigger a redeploy.)
4. Deploy. On boot the container runs migrations automatically, so the schema is
   created on Neon on the first successful deploy.

> Prefer not to use the Blueprint? Create **New → Web Service**, choose the repo,
> set **Runtime = Docker** and **Instance Type = Free**, then add the same env
> vars from `render.yaml` manually.

## 5. Seed the demo owner account

Render's **free** tier has no shell / one-off jobs, so seed by one of:

- **Temporarily** add `php artisan db:seed --force` to `docker/entrypoint.sh`
  (right after `migrate --force`), deploy once, then remove it and redeploy; or
- Run it against Neon from your machine:

  ```bash
  DB_CONNECTION=pgsql DB_URL="<your Neon string>" php artisan db:seed --force
  ```

Login: `owner@example.com` / `password` — **change this password** after first login.

---

## Free-tier limitations to know

- **Spin-down:** a free Render web service sleeps after 15 min of no traffic; the
  next request takes ~1 minute to wake it. Neon compute also scales to zero after
  5 min, adding a short cold-start on the first query. Fine for a demo/MVP.
- **Ephemeral disk:** the container filesystem resets on every deploy/restart.
  That's why sessions and cache use the **database** (`SESSION_DRIVER=database`,
  `CACHE_STORE=database`) and uploads should go to external storage later.
- **No outbound SMTP:** Render free blocks ports 25/465/587. For invoice/reminder
  emails (Phase 3–4) use an **HTTP-API** mail provider — Resend, Postmark, or
  Mailgun API — not SMTP. `MAIL_MAILER=log` is set for now.
- **No background worker / cron on free:** the Phase 4 reminder job will need a
  paid Background Worker + Cron, or an external scheduler hitting an endpoint.
- **Neon free:** 0.5 GB storage, 1 project — plenty for this CRM.

## Upgrading later

Switch the service `plan: free` → `starter` in `render.yaml` to remove spin-down
and unlock persistent disks, shell access, cron jobs, and background workers.
