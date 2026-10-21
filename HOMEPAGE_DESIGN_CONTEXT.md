# HOMEPAGE_DESIGN_CONTEXT.md

> Read-only technical and functional specification for a homepage redesign. No files were created or modified during the inspection that produced this report. This document was subsequently saved at the owner's request.

## 1. Evidence and scope

This report describes the **working tree at the time of inspection**, including existing uncommitted homepage styling changes.

Evidence reviewed:

- Project instructions, roadmap, progress, architecture, database, design, and illustration documentation.
- Composer/npm manifests and lockfiles.
- Public routes, controllers, Eloquent models, translation rules, and admin configuration.
- Homepage and shared Blade components.
- Public CSS, JavaScript, fonts, and image files.
- Selected existing feature tests.

Evidence labels:

- **Verified:** directly supported by source code, configuration, lockfiles, or inspected files.
- **Documented:** recorded in project documentation; not independently confirmed against deployed content.
- **Proposed:** design guidance, not existing functionality.
- **Unverified:** requires database access, owner confirmation, or browser inspection.

No application tests, builds, migrations, or database queries were run for the read-only analysis. No live browser inspection was performed.

## 2. Project identity and purpose

**Documented project identity**

- Owner: Amir Abbas Afshar.
- Professional title: Full-Stack Developer | Backend-Focused.
- Primary specialization: PHP & Laravel.
- Product: a personal developer portfolio with projects, technical articles, experience, education, résumé downloads, and contact functionality.
- Content is managed through a private administrator panel.

**Verified language behavior**

- Persian is the public default language.
- English has separate routes.
- Persian uses RTL; English uses LTR.
- Both interface strings and administrator-managed public text support localization.

Some original planning documents describe an English-only initial site. The current bilingual implementation supersedes that initial description.

**Unverified audience priority**

The relative importance of recruitment, freelance inquiries, and technical readership has not been established. A project-focused presentation is a design proposal, not a verified business requirement.

## 3. Project architecture

### 3.1 Framework and dependency versions

Versions below are **verified from lockfiles**, rather than inferred from dependency ranges.

| Technology | Locked version | Role |
|---|---|---|
| Laravel | 13.37.0 | Application framework, routing, validation, rendering |
| Livewire | 4.4.7 | Interactive infrastructure used by Filament |
| Filament | 5.10.2 | Administrator panel and content management |
| Tailwind CSS | 4.3.3 | Public styling |
| Tailwind Vite plugin | 4.3.3 | CSS compilation integration |
| Vite | 8.3.4 | Frontend asset build |
| Laravel Vite plugin | 3.2.0 | Laravel asset integration |

Composer requires PHP `^8.3`. The current runtime version was not checked during this inspection.

### 3.2 Application structure

**Verified**

One conventional Laravel application provides:

- Server-rendered public pages using Blade.
- Eloquent models and a shared relational database.
- A private Filament panel at `/admin`.
- Controller-based contact submissions, downloads, and media responses.
- Vite-managed CSS, JavaScript, and font assets.

Database configuration defaults to Laravel's MySQL connection. SQLite is configured for automated tests.

There is no separate public frontend application or public API in the inspected implementation.

### 3.3 Public frontend technologies

- Blade templates and anonymous Blade components.
- Tailwind utilities and custom component classes.
- CSS custom properties.
- CSS Grid and Flexbox.
- Vanilla JavaScript.
- Native HTML `<details>` for mobile navigation.
- Native HTML `<dialog>` for the contact form.

The public JavaScript entry imports no third-party libraries. No React, Vue, animation library, carousel library, or public Livewire component is used by the inspected homepage.

Filament/Livewire dependencies should not be interpreted as a requirement to build the redesigned homepage with Livewire.

### 3.4 Rendering and data flow

```text
Public request
→ locale middleware
→ controller
→ Eloquent queries and visibility rules
→ Blade homepage
→ shared public layout and components
```

The shared layout receives additional profile, site-setting, and footer-social data through a view composer.

## 4. Routing structure

| Purpose | Persian | English |
|---|---|---|
| Homepage | `/` | `/en` |
| Project listing | `/projects` | `/en/projects` |
| Project detail | `/projects/{slug}` | `/en/projects/{slug}` |
| Article listing | `/blog` | `/en/blog` |
| Article detail | `/blog/{slug}` | `/en/blog/{slug}` |
| Contact submission | POST `/contact` | POST `/en/contact` |

Shared routes:

| Route | Purpose |
|---|---|
| `/resume/{language}` | Validated language-specific PDF download |
| `/project-images/{image}` | Project image response with publication check |
| `/blog-images/{post}` | Article image response with publication check |
| `/sharing-image` | Configured social-sharing image |
| `/sitemap.xml` | Public sitemap |
| `/robots.txt` | Search crawler instructions |
| `/admin` | Administrator panel |

Internal links use a locale-aware URL helper.

Language switching:

- Switches between matching public route types.
- Preserves detail slugs when the destination translation exists.
- From an English detail without a Persian translation, switches to the Persian listing.
- Does not provide automatic translation of missing content.
- Does not explicitly preserve homepage section fragments.

## 5. Current homepage structure

The current order is:

```text
Shared header
Hero
Selected projects
Skills
About
Work experience and education
Latest articles
Contact banner
Contact dialog, normally closed
Shared footer
```

### 5.1 Section inventory

| Section | Current content and presentation | Data source |
|---|---|---|
| Header | Logo, section navigation, listing links, contact action, theme button, language switch, mobile menu | Shared layout; profile/site settings |
| Hero | Role badge, name, specialization, introduction, project CTA, contact anchor, optional résumé links and social links | Profile, social links, available résumé |
| Projects | Heading, all-projects link, up to three featured project cards | Projects, categories, technologies, images |
| Skills | Categorized skill labels with textual proficiency; large illustration | Visible categories and skills |
| About | Approach, background, expertise, interests; illustration | Profile |
| Experience | Job title, company, dates, optional employment type/location and descriptive text | Visible work experiences |
| Education | Institution, degree/field, dates, optional description | Visible education records |
| Articles | Up to three recent article cards and all-articles link | Published blog posts and categories |
| Contact | Message button, social icons, optional email link, success feedback, illustration | Social links, profile, session feedback |
| Footer | Logo, professional title, social/email links, navigation, back-to-top, current year | Shared layout data |

### 5.2 Hero details

**Verified current behavior**

- Profile text comes from the active locale.
- Missing title falls back to “Full-Stack Developer | Backend-Focused,” localized through interface translations.
- Missing specialization falls back to `PHP & Laravel`.
- Missing English name falls back to `Amir Abbas Afshar`.
- Missing Persian name falls back to localized “Portfolio.”
- Missing introduction displays an explicitly labeled placeholder.
- Primary CTA opens the project listing.
- Secondary CTA scrolls to `#contact`.
- Résumé links appear only when the current language has an available PDF.
- Social links render only for HTTP/HTTPS destinations.

Current styling includes a mint/teal gradient, dot texture, decorative rings, code symbols, and the laptop illustration.

### 5.3 Project cards

Current homepage cards display:

- First ordered project image, when present.
- Optional category.
- Title.
- Summary.
- Translated technology names.
- A project-detail link.

The whole card is effectively clickable through a stretched title link.

**Important distinction:** the model also supports role, challenges, solutions, GitHub URL, and live URL, but the current homepage card does not display these fields or separate external-link actions.

### 5.4 Article cards

Current cards display:

- Optional featured image.
- Category, falling back to “Article.”
- Localized publication date.
- Title.
- Excerpt.
- Article-detail link.

There are no reading-time, popularity, or view-count fields in the inspected article model.

## 6. Data selection and content rules

These rules are part of the functional contract, not visual styling choices.

### 6.1 Homepage query limits and ordering

| Content | Selection and ordering |
|---|---|
| Profile | Singleton record with ID `1` |
| Social links | Visible; locale label available; sort order, then ID |
| Skill categories | Visible; translated name available; must contain at least one eligible skill |
| Skills | Visible; translated name available; sort order, then ID |
| Experience | Visible; required locale company/job title; sort order, newest start date, ID |
| Education | Visible; required locale institution; sort order, newest start date, ID |
| Featured projects | Published, publication time reached, featured; sort order, newest publication, ID; maximum 3 |
| Latest articles | Published, publication time reached; newest publication and ID; maximum 3 |
| Résumé | Active locale only; filtered through file availability validation |

### 6.2 Publication and translation

- Draft and future-scheduled projects/articles are excluded.
- Persian projects require Persian title and summary.
- Persian articles require Persian title, excerpt, and content.
- Missing required translations exclude a record from Persian lists and details.
- Optional missing translated fields are omitted.
- The translation accessor does **not** automatically fall back from Persian to English.
- Slugs, URLs, dates, media, statuses, and relationships are shared across languages.

English and Persian homepages may therefore contain different numbers of projects, articles, skills, and other records.

### 6.3 Empty and optional content

The design must support:

- Missing profile fields.
- No visible skills.
- No featured projects.
- No recent articles.
- No work experience or education.
- Projects without images or categories.
- Articles without images.
- Missing social links or contact email.
- No valid résumé for the current language.

Current implementation displays section-specific empty messages. A redesign must define these states explicitly rather than assume populated data.

## 7. Design infrastructure

### 7.1 Fonts

| Context | Current font setup |
|---|---|
| Persian | Locally bundled Dana Regular and Bold; fallback fonts include Tahoma |
| English | System sans-serif stack |
| Small code-like details | System monospace utilities |

Available font files:

- `resources/fonts/Dana-Regular.ttf`
- `resources/fonts/Dana-Bold.ttf`

Only weights 400 and 700 are explicitly supplied. Current heavier utility weights may rely on browser synthesis.

Fonts use `font-display: swap`.

Persian-specific rules:

- Body line height is `1.8`.
- Email/URL inputs are LTR.
- Uppercase utility styling does not apply English-style tracking to Persian.
- Article code blocks are LTR.

### 7.2 Current color tokens

| Token | Dark value | Light override |
|---|---|---|
| Canvas | `#111719` | `#f7f8fa` |
| Ink | `#111719` | Unchanged |
| Surface | `#1b2225` | `#ffffff` |
| Paper / primary text | `#f4f1eb` | `#17252b` |
| Muted text | `#aeb8ba` | `#526168` |
| Border line | `#303a3e` | `#ccd5d9` |
| Accent | `#06c4d4` | Unchanged |
| Pink | `#cf104f` | Unchanged |
| Light pink | `#ff7396` | `#b80d45` |
| Yellow | `#ffe329` | Unchanged |

These are Tailwind theme variables, supplemented by utility colors and section-specific hardcoded colors.

The current homepage uses:

- Mint/teal hero.
- Yellow skills section.
- Pink experience section.
- Teal contact section.
- Alternating pale and indigo featured project cards.
- Subtle dotted page background.

Changing only theme variables will not restyle every surface because some selectors use explicit utility colors or hardcoded gradients.

### 7.3 Icons

The public site uses an inline SVG contact-icon component with:

- GitHub
- LinkedIn
- Instagram
- Telegram
- WhatsApp
- X/Twitter
- YouTube
- Email
- Generic external destination

Icons can be explicitly selected in the admin or inferred from destination domains.

Other controls use text glyphs such as arrows, sun, moon, and close symbols. No standalone public icon package is declared.

A skill `icon_key` field exists, but the current skill list does not render skill icons.

### 7.4 Responsive system

Verified Tailwind defaults:

| Breakpoint | Width |
|---|---|
| `sm` | 40rem, normally 640px |
| `md` | 48rem, normally 768px |
| `lg` | 64rem, normally 1024px |
| `xl` | 80rem, normally 1280px |
| `2xl` | 96rem, normally 1536px |

Current behavior:

- Main container: `max-w-6xl`, normally 1152px, including container padding.
- Horizontal padding: 24px; 32px from `sm`.
- Hero stacks below 640px and uses two columns from 640px.
- Desktop navigation starts at 768px.
- Below 768px, navigation and language switching are inside a disclosure menu.
- Project cards use two columns from 1024px.
- Article cards use two columns from 768px and three from 1280px.
- Skills/about and experience/education split into columns at 1024px.
- Contact banner uses columns from 640px.

These are current implementation details; breakpoints can be reconsidered while preserving all functions.

### 7.5 Reusable components

| Component | Responsibility |
|---|---|
| Public layout | Metadata, fonts/assets, header, footer, language/theme controls |
| Project card | Project preview with default and homepage-featured variants |
| Article card | Article preview with default and homepage-featured variants |
| Contact icon | Social/email SVG output |
| Section heading | Eyebrow, title, optional description |
| Content card | Generic linked content preview |

Reusable CSS also covers buttons, form fields, navigation, empty states, section headers, and the contact dialog.

Most homepage sections remain inline Blade markup rather than individual components.

## 8. Existing interactions and functional requirements

### 8.1 Navigation

Preserve:

- Logo link to the active-language homepage.
- About, skills, experience, and contact anchors.
- Project and article listing routes.
- Footer navigation.
- Back-to-top behavior.
- Skip-to-content link.
- Mobile access to all navigation destinations.

Existing anchor IDs:

`main`, `projects`, `skills`, `about`, `experience`, `articles`, `contact`.

Scrolling is smooth, with offset space for the sticky header. Reduced-motion preferences disable smooth scrolling and minimize transitions/animations.

### 8.2 Theme switching

- Dark is the default, independently of OS theme.
- Explicit preference is stored under `portfolio-theme`.
- An inline head script applies the saved theme before styles render.
- JavaScript updates the theme button's accessible label and pressed state.
- The browser theme-color metadata is updated.
- Storage events synchronize the preference across tabs.
- Storage failures are caught.
- Without JavaScript, the page remains dark and the toggle stays hidden.
- Filament has its own independent theme preference.

Both light and dark states are required for redesign coverage.

### 8.3 Projects and articles

Preserve:

- Current publication/translation restrictions.
- Detail-page destinations.
- Optional images and metadata.
- Protected media URLs.
- Locale-aware navigation.

Listings currently paginate at 12 records per page. The homepage itself has no pagination or filtering.

Project detail content is escaped plain text. Article detail content is rendered through Filament's sanitized rich-content renderer.

### 8.4 Contact dialog

Current behavior:

- “Send message” opens a native modal dialog.
- Close button, Escape, and outside click dismiss it.
- Background scrolling is locked while open.
- Opening focuses an invalid field when present, otherwise the name field.
- Closing restores focus to the opener.
- Validation errors and rate-limit feedback automatically reopen the dialog.
- Success feedback appears in the contact section.
- Submitted field values use Laravel's old-input mechanism.

The form is JavaScript-dependent for opening; no separate non-JavaScript contact page was found.

### 8.5 Contact validation and submission

| Field | Rules |
|---|---|
| Name | Required, 2–120 characters |
| Email | Required, RFC-valid email, maximum 254 characters |
| Subject | Required, 3–200 characters |
| Message | Required, 10–5000 characters |

Additional behavior:

- Conventional POST followed by redirect.
- CSRF protection.
- Hidden honeypot field named `website`.
- Five submissions per minute per IP.
- Messages saved to the database.
- Administrator-only message management.
- No contact-email delivery implemented.
- No attachment upload, chat, or booking functionality.

Do not portray submission as an AJAX workflow or promise an email notification.

### 8.6 Résumé downloads

- One managed PDF per language.
- Only the active language's available résumé is shown.
- Availability checks extension, file existence, MIME, and PDF signature.
- Download route rejects unsupported languages or unavailable files.
- No résumé link should be displayed as guaranteed content.

## 9. Available content and assets

### 9.1 Verified identity versus unverified biography

The owner name, professional positioning, and PHP/Laravel focus are documented in the repository.

The following were **not verified against current database records**:

- Full biography.
- Employers and dates.
- Education.
- Actual skill proficiency.
- Social destinations or contact email.
- Project names, descriptions, URLs, and publication states.
- Article titles and publication states.
- Uploaded résumé availability.

Do not treat sample content in tests or the reference image as verified owner content.

### 9.2 Static visual assets

Actual dimensions were inspected from the files.

| Asset | Actual size | Format / transparency |
|---|---|---|
| `public/images/logo.png` | 1774 × 887 | PNG, RGBA |
| `public/images/illustrations/hero-laptop.webp` | 1254 × 1254 | WebP, RGBA |
| `public/images/illustrations/skills-stack.webp` | 1254 × 1254 | WebP, RGBA |
| `public/images/illustrations/about-code.webp` | 1254 × 1254 | WebP, RGBA |
| `public/images/illustrations/contact-plane.webp` | 1254 × 1254 | WebP, RGBA |

The four illustrations total approximately **557 KiB** in the inspected working tree.

Documentation describes these as generated decorative illustrations. They are not evidence of completed software projects.

**Verified discrepancy:** Blade image attributes still declare older nonsquare dimensions for these illustrations. The actual files are square. Future implementation should reconcile declared dimensions and intended display ratios.

The design-reference file is `docs/homepage-reference.png`. It is a historical visual reference, not proof of current published content.

### 9.3 Uploaded content media

Inspection found:

- One JPEG in project-image storage.
- One JPEG in article-image storage.
- Both are 1278 × 1277 pixels.

Their associated records, public eligibility, meaning, and suitability as redesign previews remain unverified.

File presence alone does not establish that a project or article is available for public display.

### 9.4 Supported content fields

The existing data model can supply:

- Profile introduction, approach, background, expertise, interests.
- Categorized skills and textual proficiency.
- Work roles, organizations, dates, responsibilities, achievements.
- Education details.
- Project summary, body, role, challenges, solutions, technologies, gallery, GitHub/live URLs.
- Article excerpt, sanitized body, category, publication date, featured image.
- Localized site name and SEO description.
- Social destinations and language-specific résumés.

There are no inspected fields for testimonials, client logos, availability status, hourly rate, project metrics, reading time, or view counts.

Any such feature would require separately approved content or implementation work.

### 9.5 Placeholder policy

Where content is unverified, use clear design-only labels such as:

- `[Project title — placeholder]`
- `[Project screenshot — placeholder]`
- `[Article title — placeholder]`
- `[Biography — owner content required]`

Do not invent employers, achievements, years of experience, client counts, project results, credentials, or contact information.

## 10. Implementation constraints

### 10.1 Must remain intact

- One Laravel application with Blade, Tailwind, Eloquent, and Filament.
- Existing records, media, slugs, and relationships.
- Current public routes and locale behavior.
- Publication and translation restrictions.
- Server validation, CSRF, rate limiting, and honeypot.
- Protected media and résumé delivery.
- Admin authorization.
- Escaped profile/project output and sanitized article output.
- SEO titles, descriptions, canonical links, language alternates, and sharing metadata.
- Keyboard access, semantic structure, visible focus, and reduced-motion support.
- Optional-content and empty-state handling.

### 10.2 Can be redesigned

Within a future approved implementation:

- Homepage hierarchy and section order.
- Typography scale, spacing, and density.
- Colors, backgrounds, borders, shadows, and radii.
- Hero composition and illustration use.
- Project/article card composition.
- Skills and experience presentation.
- Contact banner and modal styling.
- Responsive layouts.
- Presentation of existing fields not currently shown on homepage cards.

Showing additional existing fields is feasible, but should remain conditional on real content.

### 10.3 Technical implications

- Shared layout changes affect internal pages as well as the homepage.
- Shared card changes can affect project/article listings; homepage variants already provide a separation point.
- Hardcoded surface colors require attention in both themes.
- Content length and record count vary across locales.
- Media can be absent, square, or differently proportioned; layouts need explicit crop/contain rules.
- Existing stretched card links require adjustment if separate GitHub/live buttons are added, to avoid overlapping interactive targets.
- Existing collection limits should not change accidentally during a visual redesign.
- Current code supports CSS-driven responsive design without a new frontend framework or interaction package.

## 11. Proposed redesign direction

**Proposed, not existing implementation or authorization to implement**

A restrained, technical, project-focused portfolio:

- Consistent charcoal surfaces with a limited teal accent.
- Strong typography and more breathing room.
- Clear personal introduction followed by prominent selected projects.
- Compact skills rather than a large competing color block.
- Readable experience and education.
- Supporting articles and a clear contact action.
- Reduced reliance on generic decorative illustrations.

Suggested sequence:

```text
Introduction
Selected projects
Technical expertise
About and experience, including education
Recent writing
Contact
```

A real project preview in the hero is appropriate **only after suitable published imagery is verified**. Otherwise, use the existing illustration or clearly identified abstract decoration.

The proposal does not imply new dashboards, live GitHub feeds, service offerings, fabricated metrics, or additional backend features.

## 12. Required design states

A complete design handoff should cover:

1. Persian RTL and English LTR.
2. Dark and light themes.
3. Desktop and mobile navigation.
4. Populated and empty homepage sections.
5. One, two, and three featured projects.
6. Projects/articles with and without images.
7. Long titles, descriptions, and technology lists.
8. Available and unavailable résumé.
9. Contact dialog: default, validation error, rate-limit feedback.
10. Contact success message.
11. Keyboard focus and reduced-motion behavior.
12. Missing optional translations and social destinations.

Suggested review widths: 375px, 768px, 1024px, and 1440px. These are proposed review targets, not additional implementation requirements.

## 13. Outstanding verification

Before finalizing production artwork or implementation:

- Confirm actual publishable profile and portfolio content.
- Confirm translated content coverage.
- Identify which uploaded images belong to public records.
- Confirm résumé and social-link availability.
- Inspect the running page in a browser.
- Review actual logo appearance against proposed backgrounds.
- Resolve illustration aspect-ratio discrepancies.
- Validate final layouts with realistic content lengths.

Historical progress notes report passing automated checks and earlier sandbox limitations. Those results were not rerun for this report.

**Inspection outcome:** the existing stack supports a substantial homepage visual redesign without replacing the architecture. The largest unresolved input is the verified public content inventory.

**خلاصه:** ساختار، رفتارها و محدودیت‌های فعلی بررسی شد؛ هنگام بررسی هیچ فایلی تغییر نکرد. این گزارش پس از بررسی، به درخواست مالک در پروژه ذخیره شد. موجودی واقعی محتوای منتشرشده و ظاهر صفحه در مرورگر هنوز تأیید نشده است.
