# Design direction

Dark, minimal, editorial developer portfolio. Strong headings and readable body text; restrained accent color (cool teal or blue), deep charcoal background, warm light text, and clear focus states. Use a simple text brand; no portrait required.

## Layout

- Sticky responsive header with accessible mobile menu and section anchors.
- Hero with name, title, specialization, short introduction, social links, projects CTA, and available resume links.
- Home sections: About, categorized skills, experience timeline, education, selected projects, latest articles, contact.
- Project and article indexes use spacious cards; detail pages emphasize readable long-form content and clear metadata.
- Footer repeats identity, social links, and current year.

## Components and behavior

Use a consistent content width, generous spacing, subtle borders/surfaces, restrained hover transitions, semantic headings, keyboard focus, reduced-motion support, and responsive layouts. Avoid percentage skill bars, decorative overload, generic admin styling, and empty link buttons. Images use useful alt text, responsive sizing, and lazy loading outside the first viewport.

Use Tailwind tokens for color/type/spacing. Prefer system or locally bundled fonts to avoid a runtime external dependency. Keep markup easy to adapt to `dir="rtl"` later.
