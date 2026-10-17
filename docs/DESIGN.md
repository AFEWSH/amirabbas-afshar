# Design direction

Dark developer portfolio based on `homepage-reference.png`: deep charcoal with a subtle dotted texture, bright teal, pink, and yellow accents, rounded color-block sections, strong headings, and readable body text. Keep color contrast and clear focus states. Use a text brand with a small code mark; no portrait is required.

## Layout

- Sticky responsive header with a pill-style navigation, accessible mobile menu, contact action, locale switch, and section anchors.
- Hero with name, title, specialization, short introduction, social links, projects CTA, and available resume links.
- Home sections follow the reference order: hero, selected projects, categorized skills, about, experience and education, latest articles, contact.
- Project and article indexes use spacious cards, illustrated headings, and accessible pagination; detail pages use clear metadata, image galleries, and readable long-form content.
- Footer repeats identity, social links, and current year.

## Components and behavior

Use a consistent content width, generous spacing, rounded surfaces, restrained hover transitions, semantic headings, keyboard focus, reduced-motion support, and responsive layouts. Reuse the reference's accent colors while keeping each section readable. Avoid percentage skill bars, generic admin styling, and empty link buttons. Images use useful alt text, responsive sizing, and lazy loading outside the first viewport.

Use Tailwind tokens for color/type/spacing. Keep English on the system font stack and use the locally bundled Dana Regular/Bold fonts for Persian, with no runtime external dependency. Keep markup easy to adapt to `dir="rtl"` later.
