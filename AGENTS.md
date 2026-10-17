# Project instructions

- Read `docs/ROADMAP.md`, `docs/PROGRESS.md`, and relevant documentation before each phase. Inspect the codebase first.
- Implement only the phase explicitly requested by the owner. Never begin the next phase automatically.
- Keep one conventional Laravel application with Blade, Tailwind, Livewire only for useful interaction, Filament, and a shared relational database.
- Prefer controllers, Eloquent, Blade components, and Filament resources/pages. Avoid unnecessary packages, APIs, service layers, repository patterns, and complex CMS or permissions systems.
- Preserve existing functionality and user data. Never run destructive database commands or delete user data without explicit approval. Never expose secrets.
- Do not invent biography, employment, achievements, projects, education, skill levels, or contact details. Clearly label temporary placeholder content.
- Use English for the initial public site; keep Laravel localization and future RTL support feasible without building multilingual content management now.
- Use server validation, authorization, CSRF protection, safe uploads, and escaped output. Restrict public content to published records.
- After each phase, run relevant tests and builds, fix caused failures, update `docs/PROGRESS.md`, and summarize in Persian.
