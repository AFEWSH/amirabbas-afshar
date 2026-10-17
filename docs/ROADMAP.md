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

## Approved visual refresh

This work refreshes the existing public interface to follow `docs/homepage-reference.png`. It is separate from the completed feature delivery phases above. Keep the existing Persian/English routes, content rules, and admin-managed data.

### Visual Phase 1 — Shared visual foundation (complete)

Update shared design tokens, page background, header/navigation, buttons, and footer. Preserve RTL/LTR behavior, the language switch, keyboard navigation, and mobile access. Acceptance: shared layout builds and existing public route tests pass in both locales.

### Visual Phase 2 — Home page (complete)

Restyle the home sections to match the reference's hero, selected projects, skills, about, experience, articles, and contact blocks. Use only configured profile and published content; retain graceful empty states. Acceptance: responsive Persian and English home pages render correctly and remain accessible.

### Visual Phase 3 — Internal pages (complete)

Restyle project and article indexes, detail pages, cards, metadata, and pagination to use the shared visual language. Acceptance: both locales and all existing publication/translation rules continue to work.

### Visual Phase 4 — Review (complete)

Review desktop/mobile layouts, RTL/LTR, contrast, keyboard focus, reduced motion, and page links. Run relevant tests and production build, then record completion in `docs/PROGRESS.md`.
