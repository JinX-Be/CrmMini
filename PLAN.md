# Mini CRM — Plan

A lightweight CRM for service businesses, starting with **window cleaners** and designed so the same codebase can later serve other niches (solar panel cleaners, dog groomers, etc.), with the niche resolved by URL or config.

## 1. Recommended stack

**Laravel (PHP).** It's the best fit for your goals:

- Auth, migrations, validation, and an ORM (Eloquent) out of the box — less plumbing.
- **Queues + scheduler** make the "remind users to reschedule" feature clean and reliable.
- A first-class API layer (Sanctum + API Resources). When you want a dedicated mobile app later, the CRM becomes the backend and the app just consumes the same JSON API. No rewrite.
- Blade for server-rendered web UI now; swap/add an SPA or native app later without touching business logic.

Supporting choices: **MySQL/MariaDB** (or PostgreSQL), **Mailgun/Postmark** for email, **Twilio/MessageBird** for SMS, **FullCalendar** (JS) for the calendar view, **dompdf** or **Browsershot** for invoice PDFs.

## 2. Niche / template strategy

Build window cleaners cleanly *now*, but structure for multi-niche from day one so you never refactor the core.

**Core principle:** the *engine* (users, appointments, invoices, reminders) is identical across niches. Only three things vary per niche: branding/template, the **custom fields** on a customer, and copy/terminology.

- A `niche` concept (key, label, theme, field schema) — initially a single hardcoded/config value (`NICHE=window_cleaning`).
- Niche-specific data stored as **flexible custom fields** rather than hardcoded columns (e.g. window cleaners: "number of windows", "floors", "frequency"; dog groomers: "breed", "coat type"). Store as a JSON column or an EAV-style `custom_field` / `custom_field_value` pair.
- View templates selected by niche: `resources/views/niches/window_cleaning/...` with a fallback to a shared default.

**Later — resolve niche by URL or config:**

- A small middleware reads the hostname/subdomain (e.g. `windows.app.com`, `groomers.app.com`) → maps to a niche → sets the active template + field schema for the request.
- Until then, a single `config/niche.php` (or `.env`) value drives everything. Switching from config-based to URL-based is then just changing *where* the niche is resolved, not how it's used.

## 3. Data model (core tables)

- **users** — the business owner/staff who log in (Laravel auth). Roles: owner, staff.
- **customers** — the end clients being serviced. Standard fields (name, email, phone, address) + a `custom_fields` JSON column for niche-specific data.
- **services** — what's offered (name, default price, default interval in days) — drives invoices and reminders.
- **appointments** — customer, service, datetime, duration, status (scheduled/completed/cancelled), notes. Source of the calendar view.
- **invoices** — linked to an appointment/customer; line items, total, status (draft/sent/paid), PDF.
- **invoice_items** — description, qty, unit price.
- **reminders** — tracks the next-reschedule due date per customer/service and whether a notification was sent.
- **niches** (added in the multi-niche phase) — key, label, theme, field schema.

## 4. Features

**Users & customers with niche fields.** Owner logs in, manages customers. The customer form renders standard fields plus the active niche's custom fields from the field schema.

**Appointments + calendar view.** Create/edit appointments tied to a customer and service. A month/week/day calendar (FullCalendar pulling from an `/appointments` JSON endpoint). Mark appointments completed.

**Invoices after an appointment.** From a completed appointment, generate a draft invoice pre-filled from the service price. Edit line items, mark sent/paid, export PDF, email to the customer.

**Reschedule reminders by interval.** Each service has a default interval (e.g. window cleaning every 8 weeks). When an appointment is completed, the customer's next-due date is set. A daily **scheduled job** finds customers due for rescheduling and sends an **email and/or SMS** prompt to rebook. Sends are logged so nobody gets double-notified.

## 5. Phased roadmap

**Phase 0 — Foundation.** Laravel install, DB, auth, base layout, niche config scaffold (single niche), seed data.

**Phase 1 — Customers.** CRUD + custom-field schema mechanism (window-cleaning fields), search/list.

**Phase 2 — Appointments & calendar.** Appointment CRUD, services, FullCalendar view, status flow.

**Phase 3 — Invoices.** Generate from completed appointment, line items, statuses, PDF + email.

**Phase 4 — Reminders.** Service intervals, next-due tracking, daily scheduled job, email + SMS notifications, send log.

**Phase 5 — Multi-niche.** Promote niche from config to URL/subdomain resolution, per-niche templates/themes, add a second niche (e.g. solar or grooming) to prove the abstraction.

**Phase 6 — API & app-readiness.** Expose the core as a clean JSON API (Sanctum tokens) so a dedicated mobile/desktop app can be built on the same backend.

## 6. Key design decisions to keep it future-proof

- Keep niche-specific logic out of the core — only in field schemas, templates, and copy.
- Treat the web app as the first client of an API, not as the whole product.
- Use the queue/scheduler for all notifications from the start (testable, reliable, scalable).
- Make notification channels (email, SMS) pluggable so adding push later is trivial.
