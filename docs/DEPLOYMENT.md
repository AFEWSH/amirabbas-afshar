# Deployment

Deploy the Laravel application with the web server document root set to `public/`. Use PHP 8.3 or newer with the extensions required by Laravel and Filament, Composer, Node.js/npm for asset compilation, and a MySQL-compatible database. Serve the site over HTTPS.

## Configure

1. Install dependencies with `composer install --no-dev --optimize-autoloader` and `npm ci`.
2. Create the server's `.env` from `.env.example` without committing it. Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` to the final HTTPS origin, `SESSION_SECURE_COOKIE=true`, and the server's database connection values. Keep `APP_KEY` and database credentials private. Generate the application key once with `php artisan key:generate` for a new installation; preserve the existing key on updates.
3. Give the PHP process write access to `storage/` and `bootstrap/cache/`. Keep the private upload directories under `storage/app/` outside the web root. No `storage:link` is required for the controlled image and resume routes.
4. Run `npm run build`, then `php artisan migrate --force`. This applies pending additive migrations; back up the database and uploaded files before deployment. Never use destructive migration commands on production data.
5. Run `php artisan optimize`. Restart long-running PHP workers or PHP-FPM so the new code and configuration load. The Filament asset publishing command runs through Composer's autoload script; use `php artisan filament:assets` if assets need regeneration.
6. For a new database, create the administrator with `php artisan portfolio:create-admin` from the server terminal. Existing installations keep their current administrator.

## Check before announcing the site

- Add verified profile, experience, skills, projects, articles, contact details, site settings and an optional sharing image in the admin panel. Remove or replace clearly labeled placeholder sections as appropriate. Confirm publication dates and visibility settings.
- Check `/`, `/projects`, `/blog`, `/sitemap.xml`, `/robots.txt`, the contact form, and any uploaded images/resumes over the production HTTPS URL. Verify draft and future content remains inaccessible.
- Confirm the generated page source has the expected title, description, canonical URL and sharing image. Keep `APP_URL` aligned with the public origin to avoid incorrect absolute URLs.
- Back up both the database and `storage/app/` on a schedule. Keep logs and `.env` private; monitor `storage/logs/` for runtime errors.
