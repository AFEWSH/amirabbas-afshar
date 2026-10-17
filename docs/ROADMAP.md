# Roadmap

Only start a phase when the owner requests it. Before each phase, read `AGENTS.md`, this file, `docs/PROGRESS.md`, and inspect the codebase. After each phase, run relevant tests/builds and update progress.

## Phase 0 — Planning

Create project, architecture, database, design, roadmap, progress, and agent instructions. Acceptance: files exist, scope and data relationships are coherent, environment versions and decisions are recorded; no Laravel installation or application code.

## Phase 1 — Foundation

Confirm stable compatible versions and database server; install Laravel, Livewire, Tailwind, Filament; configure MySQL-compatible connection, Vite, Git, basic admin login and explicit single-admin authorization. Acceptance: app boots, asset build and baseline tests pass, admin login works, public registration is absent, no portfolio features yet.

## Phase 2 — Public design

Build responsive Blade layout and home/project/blog page shells with clearly labeled placeholders. Add accessible navigation, mobile menu, dark theme, and reusable UI components. Acceptance: routes render, visual layout works at mobile/desktop widths, all placeholder claims are labeled, build and route tests pass.

## Phase 3 — Profile and skills

Add profile, social links, skill categories/skills schema and Filament management; replace related placeholders with configured data. Acceptance: admin CRUD/settings work, only visible configured records show, proficiency labels are textual, empty states are graceful, focused tests pass.

## Phase 4 — Experience, education, projects

Add experience, education, project categories/projects, technologies, images, details and admin management. Acceptance: ordered timeline and featured projects render; published projects alone appear on public routes; private/unpublished slugs are inaccessible; optional GitHub/live links hide; upload validation and focused tests pass.

## Phase 5 — Articles

Add blog categories/posts, Filament editor, listing/details, publication rules and SEO fields. Acceptance: drafts/future posts are hidden; published posts render safely; category relations and upload validation work; focused tests pass.

## Phase 6 — Contact and settings

Add contact form/messages, resume files/downloads, and site settings. Acceptance: validation, rate limiting and honeypot protect submissions; success/error feedback works; only valid PDF uploads and existing resume links are available; admin message access is restricted; focused tests pass.

## Phase 7 — Readiness

Add page SEO/OG/canonical metadata, sitemap, robots.txt; review security, query efficiency, images, accessibility and deployment configuration. Acceptance: unique titles/descriptions and sitemap cover public content only; production assets build; focused suite passes; deployment instructions contain no secrets.
