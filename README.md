# Amir Abbas Afshar — Personal Portfolio

A bilingual portfolio and technical blog for Amir Abbas Afshar, a full-stack developer focused on PHP and Laravel. One Laravel application serves the public website and a private Filament administration panel, backed by a shared relational database.

The public site supports Persian (RTL) and English (LTR), with a responsive dark interface, custom 3D illustrations, and Dana typography for Persian content.

## Features

- Profile, technical skills, work experience, and education managed through the admin panel.
- Project listings and detail pages with categories, technologies, and image galleries.
- Technical articles with categories and rich content editing.
- Publication rules that keep drafts and future posts off public pages.
- Contact form in a modal, with server validation, rate limiting, and honeypot protection. Messages are saved in the admin panel; email delivery is not configured.
- Separate Persian and English PDF résumés with controlled downloads.
- Social links with selectable icons, visibility controls, and ordering, displayed beside the contact action and in every public footer.
- Page metadata, canonical URLs, language alternates, sitemap, and social sharing image support.
- A single administrator account with no public registration.

## Technology

| Component | Technology |
| --- | --- |
| Backend | PHP 8.3+, Laravel 13 |
| Public interface | Blade, Tailwind CSS 4, native JavaScript |
| Administration | Filament 5, Livewire 4 |
| Database | MySQL-compatible database |
| Asset build | Vite 8 |
| Tests | PHPUnit 12, SQLite in memory |

Dependency versions are pinned in `composer.lock` and `package-lock.json`.

## Local setup

Requirements: PHP 8.3+ with the extensions required by Composer dependencies, Composer 2, Node.js 22.12+ with npm, and a MySQL-compatible database. PHP's SQLite extension is also required to run tests.

From the repository root:

```bash
composer install
npm ci
cp .env.example .env
php artisan key:generate
```

Create an empty database and set your local connection details in `.env`:

```dotenv
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=personal_portfolio
DB_USERNAME=portfolio_user
DB_PASSWORD=your_local_password
```

Apply migrations and create the administrator:

```bash
php artisan migrate
php artisan portfolio:create-admin
```

The administrator command prompts for a name, email, and password. It refuses to create another administrator when one already exists.

Run Laravel and Vite in separate terminals:

```bash
php artisan serve
```

```bash
npm run dev
```

Open `http://localhost:8000` for the public site and `/admin` for administration. To serve compiled assets instead of running Vite, use `npm run build`.

## Languages and routes

| Page | Persian | English |
| --- | --- | --- |
| Home | `/` | `/en` |
| Projects | `/projects` | `/en/projects` |
| Project | `/projects/{slug}` | `/en/projects/{slug}` |
| Articles | `/blog` | `/en/blog` |
| Article | `/blog/{slug}` | `/en/blog/{slug}` |

The language switch preserves the current page where a translation is available. Persian fields are entered alongside English fields in the admin panel. Records missing required Persian content are omitted from Persian listings; untranslated detail pages return 404. Slugs are shared between languages. The admin interface remains English.

## Managing content

Log in at `/admin` to manage profile information, skills, experience, education, projects, articles, contact messages, résumés, social links, and site settings.

For social links, open **Social Links**, enter the label and URL, select a **Network icon** or leave it empty for URL detection, set the display order, and enable **Visible on site**. Fill **Label (Persian)** to show the link on Persian pages. Supported icons include GitHub, LinkedIn, Instagram, Telegram, WhatsApp, X/Twitter, YouTube, and a generic website icon.

Project and article visibility follows their publication settings and dates. Replace temporary placeholder content with verified information before publishing the site.

## Assets and uploads

- Site logo: `public/images/logo.png`.
- Homepage illustrations: `public/images/illustrations/`; generation prompts are recorded in [Illustrations](docs/ILLUSTRATIONS.md).
- Persian fonts: `resources/fonts/`, bundled by Vite.
- Uploaded project images, article images, résumés, and sharing images: dedicated private directories under `storage/app/`, served through controlled application routes.

A `storage:link` is not required for the current upload setup. Back up both the database and uploaded files.

## Checks

```bash
npm run build
php artisan test
vendor/bin/pint --test
```

Tests use SQLite in memory, configured in `phpunit.xml`. If you previously cached local configuration or routes, clear those generated caches before testing so the test environment can load correctly:

```bash
php artisan config:clear
php artisan route:clear
```

## Deployment

See [Deployment](docs/DEPLOYMENT.md) for the production procedure. Set the web server document root to `public/`, configure HTTPS and production environment values, build assets, apply pending migrations, and cache the application configuration and routes. Preserve the existing application key when updating an installation.

## Project layout

```text
app/Filament/          Admin resources and pages
app/Http/              Public controllers and locale middleware
app/Models/            Eloquent models and publication/localization scopes
app/Support/           Locale-aware public URLs
resources/views/       Blade pages and shared components
resources/css/         Public styles and typography
resources/js/          Public interactions, including the contact modal
routes/                Public routes and console commands
database/migrations/   Database schema changes
tests/Feature/         Feature and authorization tests
docs/                  Architecture, design, deployment, and progress
```

## Documentation

- [Architecture](docs/ARCHITECTURE.md)
- [Database](docs/DATABASE.md)
- [Design](docs/DESIGN.md)
- [Roadmap](docs/ROADMAP.md)
- [Progress and outstanding verification](docs/PROGRESS.md)

## License

Copyright © Amir Abbas Afshar. All rights reserved. This repository is proprietary, as declared in `composer.json`.
