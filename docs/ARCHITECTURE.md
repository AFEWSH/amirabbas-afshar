# Architecture

One Laravel application serves Blade public pages and a Filament admin panel. Both use the same database, Eloquent models, migrations, validation, Laravel Storage, and Vite assets. Tailwind styles the public site; a native HTML disclosure handles the mobile menu. The contact form uses a conventional POST controller with redirect feedback, while Livewire powers Filament interactions.

## Application shape

- Public routes: `/`, `/projects`, `/projects/{slug}`, `/blog`, `/blog/{slug}`, contact submission, resume downloads, and `/sitemap.xml`.
- Public pages emit page-specific title, description, canonical and sharing metadata. The sitemap lists only currently published project and article details; robots.txt advertises it. The configured default sharing image is served through a checked route from private storage.
- Controllers handle page queries and explicit download/submission actions; Blade views/components render content.
- Filament resources manage collections. Filament pages manage singleton profile/site settings and resume files.
- Eloquent relationships handle categories, technologies, images, and content. Published scopes and route lookup prevent draft exposure.
- Contact messages persist in the database. Apply validation, Laravel rate limiting, and a honeypot field; no mail dependency initially.
- Uploaded images and resumes use Laravel Storage. Validate MIME, size, and image dimensions; downloads are served through controlled routes. Project and article images use private local disks and routes that verify their parent content is published. Resume PDFs use a private disk, verified signature, and controlled download route. Project long-form fields are plain text and escaped in Blade. Article content uses Filament's rich editor with attachments disabled and is rendered through Filament's sanitized `RichContentRenderer`.
- Use Laravel localization files for UI strings when introduced. English is the only initial locale; choose CSS/layout patterns compatible with later RTL.

## Version decision

Observed on 2026-10-17: PHP 8.4.26, Composer 2.9.5, Node 22.22.3, npm 12.2.0, and MariaDB client 10.11.14. Phase 1 must confirm the actual database server and check current stable compatibility before pinning Laravel, Livewire, Filament, and Tailwind versions. Do not upgrade the operating system automatically.

Phase 1 installed Laravel 13.37.0, Livewire 4.4.7, Filament 5.10.2, Tailwind 4, and Vite 8.3.4. The application defaults to the MySQL driver; the local server cannot be reached from the Codex sandbox, so live MySQL migration remains unverified.

## Simplicity choices

No repository layer or service layer for CRUD. No public API, separate frontend, caching infrastructure, or custom permissions framework. A single admin identity is authorized explicitly for Filament access.
