# Database design

All tables use an `id` primary key and timestamps unless noted. Add foreign keys and indexes for relationships, publication queries, and display ordering. Migration details are finalized in the implementing phase.

| Table | Important fields | Relationship / notes |
| --- | --- | --- |
| `users` | name, email (unique), password, is_admin | One authorized administrator; only accounts explicitly marked `is_admin` can access Filament. |
| `profiles` | full_name, title, specialization, introduction, background, expertise, interests, approach, contact_email | Singleton profile; long text for editable sections. |
| `social_links` | label, url, icon_key, sort_order, is_visible | Administrator-configured links, including GitHub and LinkedIn. |
| `skill_categories` | name, slug (unique), sort_order, is_visible | Has many skills. |
| `skills` | skill_category_id, name, icon_key, proficiency, sort_order, is_visible | Proficiency: familiar/intermediate/advanced. |
| `work_experiences` | company, job_title, employment_type, location, start_date, end_date, is_current, description, responsibilities, achievements, sort_order, is_visible | Text fields can hold concise prose/bullets. |
| `educations` | institution, degree, field_of_study, start_date, end_date, description, sort_order, is_visible | Independent timeline entries. |
| `project_categories` | name, slug (unique) | Has many projects. |
| `projects` | project_category_id, title, slug (unique), summary, body, role, challenges, solutions, github_url, live_url, is_featured, status, published_at, sort_order, seo_title, seo_description | `status` draft/published; nullable external URLs. |
| `technologies` | name, slug (unique) | Shared vocabulary for projects. |
| `project_technology` | project_id, technology_id | Unique pair. |
| `project_images` | project_id, path, alt_text, sort_order | Has many ordered images. |
| `blog_categories` | name, slug (unique) | Has many posts. |
| `blog_posts` | blog_category_id, title, slug (unique), excerpt, content, featured_image_path, featured_image_alt, status, published_at, seo_title, seo_description | Public only when published and publication time has arrived. Image description supports useful alt text. |
| `contact_messages` | name, email, subject, message, read_at | Stored submissions; no public read access. |
| `resume_files` | language (unique: `en`/`fa`), path, original_name | At most one PDF per language. |
| `site_settings` | site_name, default_seo_description, og_image_path | Singleton; keep narrow and extend only when needed. |

Use normalized category and technology relations. Do not add seed data claiming real experience or skill proficiency. Store file paths, not binary files. Index `status` with `published_at`, category foreign keys, `sort_order` where queried, and contact `created_at`.

## Public bilingual content extension

The additive `2026_10_17_000005_add_persian_public_content.php` migration adds nullable `_fa` counterparts to public text fields in profile, site settings, social links, skill categories and skills, experience, education, project categories and technologies, projects and their image alt text, blog categories and posts. Existing columns remain the English source and existing data is preserved. Slugs, dates, URLs, status, relationships, and uploads are shared between languages.

On Persian routes, projects require `title_fa` and `summary_fa`; articles require `title_fa`, `excerpt_fa`, and `content_fa`. Untranslated records are omitted from Persian lists and details return 404. Other untranslated optional fields are omitted. English routes continue to use the original columns. Both languages still obey the existing publication and visibility rules.
