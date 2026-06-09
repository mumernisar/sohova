# Automated Lead Enrichment & Onboarding (MVP)

When someone fills in the public contact form, this system automatically
enriches the lead (looks up the company size), files it in an internal
dashboard, and emails the lead a welcome message — no manual copy/paste, no
data-entry errors.

---

## How the data flows (plain-English version)

1. **A lead submits the contact form.** It POSTs to an n8n webhook.
2. **n8n cleans the data** — normalises the email, derives the company domain.
3. **n8n enriches the lead** via the Apollo API: company size + industry,
   looked up from the domain. (This replaces the manual "search LinkedIn for the
   company size" step.)
4. **n8n files the lead** by sending it to the Laravel app, which saves it.
   Re-submissions from the same email update the existing record instead of
   creating a duplicate.
5. **n8n emails the lead** a welcome message via Resend (from your domain).
6. **The sales team works the lead** in the Filament dashboard — viewing,
   filtering, and moving it `new → contacted → qualified`.

**If the Laravel app is down:** n8n retries 3×. If it still fails, it emails the
sales-ops team an alert with the full lead details (so the lead is never lost)
and tells the form the submission was *accepted* — the visitor never sees an
error.

```
Contact form
     │ POST
     ▼
[Webhook] → [Normalize] → [Apollo enrich] → [Map] → [Create Lead in Laravel] ─success─→ [Welcome email] → [200]
                                                              │
                                                          (3 retries)
                                                              │ still failing
                                                              └─error─→ [Alert sales-ops via Resend] → [202 accepted]
```

---

## What's in this repo

```
n8n/lead-enrichment-workflow.json   ← import into n8n
laravel/                            ← the custom Laravel + Filament code
README.md
```

The `laravel/` folder is the **custom code only** — you drop it into a fresh
Laravel + Filament install (commands below).

---

## Setup

### 1. Laravel + Filament

> **Filament version note.** Filament v4/v5 is now current, but it uses a new
> "separate schema files" architecture. The resource in this repo is written for
> **Filament v3** (stable, still fully supported) so it works as-is. Pin v3 in
> the install command below. If you'd rather use v4/v5, skip the provided
> `LeadResource.php` and instead run `php artisan make:filament-resource Lead
> --generate` — that auto-builds a version-correct panel straight from the model.

```bash
composer create-project laravel/laravel lead-app
cd lead-app

composer require filament/filament:"^3.2"
php artisan filament:install --panels
php artisan make:filament-user      # creates your admin login
```

Copy the files from this repo's `laravel/` folder into the matching paths, then:

- Merge `bootstrap-app.snippet.php` into your `bootstrap/app.php` (registers the
  `lead.token` middleware alias).
- Merge the `lead_api` block from `config/services.php`.
- Add `LEAD_API_TOKEN` from `.env.example.additions` to your `.env`.

```bash
php artisan migrate
php artisan serve   # http://localhost:8000 → panel at /admin
```

### 2. n8n workflow

1. **Workflows → Import from File** → `n8n/lead-enrichment-workflow.json`.
2. **Enrich via Apollo** node → set the `X-Api-Key` header to your Apollo API key.
3. **Create Lead in Laravel** node → set the URL to your app
   (`https://your-app.test/api/leads`) and the `Authorization` header to
   `Bearer <your LEAD_API_TOKEN>`.
4. **Send Welcome Email** and **Alert Team** nodes → set the `Authorization`
   header to `Bearer <your Resend API key>` and change the `from` addresses to
   your verified Resend domain.
5. **Activate** the workflow and copy the **Production webhook URL** — that's
   what your contact form posts to.

### 3. Test end-to-end

```bash
curl -X POST <your-n8n-production-webhook-url> \
  -H "Content-Type: application/json" \
  -d '{"name":"Jane Doe","email":"jane@stripe.com","company":"Stripe","message":"Interested in a demo"}'
```

Use a domain Apollo will recognise (e.g. `stripe.com`) to see real enrichment.
The lead appears in `/admin` and a welcome email is sent. To test the failure
path, stop `php artisan serve` and submit again — the sales-ops alert fires and
the webhook returns `202 accepted`.

---

## Design notes

- **Enrichment via API, not scraping.** The brief describes the *manual* step as
  "search the company LinkedIn profile for the company size." We automate the
  *outcome* (company size) using Apollo's organization-enrichment API keyed on
  the email domain. Scraping LinkedIn directly violates their ToS, breaks every
  time their markup changes, and needs proxies/headless browsers — the opposite
  of "pragmatism over perfection." An enrichment API is the right tool. If
  Apollo has no match, the lead is still saved with `enrichment_status = failed`
  so the team knows to look it up manually. Apollo is swappable for
  Clearbit/People Data Labs in one node.
- **Clean payload + idempotency.** Laravel validates every field
  (`StoreLeadRequest`) and the endpoint upserts on email (`updateOrCreate`), so
  n8n retries can never create duplicates.
- **Auth.** Lightweight bearer-token middleware (`EnsureApiToken`), token in
  `.env` — appropriate for machine-to-machine MVP traffic.
- **Auditing.** The original form payload is stored in `raw_payload` (JSON).
- **Error handling lives in n8n** (retry → alert → graceful 202), so a
  downstream outage never breaks the visitor experience.

## How AI tooling was used

Claude was used to scaffold the Laravel model, migration, validated request,
token middleware, and Filament resource, and to generate the importable n8n
workflow JSON — including the Apollo enrichment mapping, the Resend email
payloads, and the retry/alert error branch. It also verified current Apollo,
Resend, and Filament specifics so the integrations match 2026 APIs.
