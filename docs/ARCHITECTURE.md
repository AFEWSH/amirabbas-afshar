# Architecture

One Laravel application serves Blade public pages and a Filament admin panel. Both use the same database, Eloquent models, migrations, validation, Laravel Storage, and Vite assets. Tailwind styles the public site; Alpine handles the mobile menu, and Livewire is reserved for interaction where it improves the experience (the contact form is a likely case).

## Application shape

- Public routes: `/`, `/projects`, `/projects/{slug}`, `/blog`, `/blog/{slug}`, contact submission, resume downloads, and `/sitemap.xml`.
- Controllers handle page queries and explicit download/submission actions; Blade views/components render content.
- Filament resources manage collections. Filament pages manage singleton profile/site settings and resume files.
- Eloquent relationships handle categories, technologies, images, and content. Published scopes and route lookup prevent draft exposure.
- Contact messages persist in the database. Apply validation, Laravel rate limiting, and a honeypot field; no mail dependency initially.
- Uploaded images and resumes use Laravel Storage. Validate MIME, size, and image dimensions; downloads are served through controlled routes. Escape public text and sanitize or render rich article/project HTML safely.
- Use Laravel localization files for UI strings when introduced. English is the only initial locale; choose CSS/layout patterns compatible with later RTL.

## Version decision

Observed on 2026-10-17: PHP 8.4.26, Composer 2.9.5, Node 22.22.3, npm 12.2.0, and MariaDB client 10.11.14. Phase 1 must confirm the actual database server and check current stable compatibility before pinning Laravel, Livewire, Filament, and Tailwind versions. Do not upgrade the operating system automatically.

Phase 1 scaffold targets Laravel 13, Livewire 4, Filament 5, and Tailwind 4 with Vite 8. These are declared but cannot be installed or verified until package registries are reachable. The application defaults to the MySQL driver; no database server has been verified.

## Simplicity choices

No repository layer or service layer for CRUD. No public API, separate frontend, caching infrastructure, or custom permissions framework. A single admin identity is authorized explicitly for Filament access.
