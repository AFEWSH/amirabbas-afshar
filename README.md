# Personal Portfolio

My personal portfolio website, built to showcase my professional experience, technical skills, projects, and software development journey.

The project is developed using Laravel, Livewire, Tailwind CSS, and Filament, with a focus on clean design, simplicity, performance, and maintainability.

## About Me

I'm **Amir Abbas Afshar**, a Full-Stack Developer with a strong focus on backend development using PHP and Laravel.

I specialize in building web applications, designing databases, developing APIs, and solving real-world software engineering challenges.

This portfolio serves as a central place to present my work, share technical knowledge, and connect with other developers and potential collaborators.

## Tech Stack

| Technology | Purpose |
|------------|---------|
| PHP | Backend Programming |
| Laravel | Backend Framework |
| Livewire | Dynamic UI Components |
| Tailwind CSS | UI Styling |
| Alpine.js | Lightweight Interactivity |
| Filament | Admin Panel |
| MySQL | Database |
| Vite | Asset Bundling |

## Features

The following features are planned for the portfolio.

### Public Website

- Modern and responsive design
- Personal introduction and professional background
- Categorized technical skills
- Professional work experience
- Education history
- Project portfolio with dedicated detail pages
- Technical blog and articles
- Contact form
- Downloadable resume
- Social media and GitHub links
- SEO-friendly pages

### Admin Panel

A Filament-powered admin panel for managing website content.

Planned management features:

- Personal profile
- Technical skills and categories
- Work experience
- Education
- Projects and project categories
- Blog posts and categories
- Contact messages
- Resume files
- Social links
- Website settings

The admin panel is designed for a single administrator, keeping content management simple and efficient.

## Project Structure

The application follows Laravel's conventional project structure.

```text
app/
├── Filament/
├── Http/
├── Livewire/
└── Models/

database/
├── factories/
├── migrations/
└── seeders/

resources/
├── css/
├── js/
└── views/

routes/
├── console.php
└── web.php

docs/
├── PROJECT.md
├── ARCHITECTURE.md
├── DATABASE.md
├── DESIGN.md
├── ROADMAP.md
└── PROGRESS.md
```

The structure may evolve as development progresses.

## Getting Started

### Requirements

- PHP
- Composer
- Node.js and npm
- MySQL
- Required PHP extensions

Use versions compatible with the Laravel and Filament releases installed in the project.

### Installation

Clone the repository:

```bash
git clone https://github.com/AFEWSH/amirabbas-afshar.git
cd amirabbas-afshar
```

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

Create the environment configuration:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

Configure your database connection in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=personal_portfolio
DB_USERNAME=root
DB_PASSWORD=
```

Run database migrations:

```bash
php artisan migrate
```

Create an administrator account after Filament has been installed:

```bash
php artisan make:filament-user
```

Start the development environment:

```bash
composer run dev
```

If the project does not define a `dev` Composer script, start Laravel and Vite separately:

```bash
php artisan serve
npm run dev
```

> Installation instructions will be verified and updated as the implementation progresses.

## Development Roadmap

The project is developed incrementally.

| Phase | Description |
|-------|-------------|
| 0 | Planning and Documentation |
| 1 | Laravel Setup and Admin Authentication |
| 2 | Public Website UI and Design |
| 3 | Profile and Skills Management |
| 4 | Experience, Education, and Projects |
| 5 | Technical Blog |
| 6 | Contact, Resume, and Website Settings |
| 7 | SEO, Testing, Security, and Deployment |

Detailed implementation progress is documented in:

[Development Roadmap](docs/ROADMAP.md)

## Design Philosophy

The portfolio follows a few core principles:

**Simplicity**

Keep the architecture straightforward and avoid unnecessary complexity.

**Professional Design**

Create a modern, minimal, and responsive interface.

**Maintainability**

Follow Laravel conventions and write readable, maintainable code.

**Performance**

Prioritize fast loading, efficient database queries, and lightweight frontend interactions.

**Practicality**

Focus on features that provide real value instead of unnecessary abstractions.

## Architecture

The project uses a single Laravel application containing:

- A public portfolio website
- A Filament administration panel
- A shared MySQL database

Blade and Tailwind CSS handle the public interface, while Livewire provides interactive functionality where necessary.

Filament is responsible for content management.

The application does not require a separate frontend application or independent API.

## Security

The project is designed with the following security considerations:

- Restricted administrator access
- No public administrator registration
- CSRF protection
- Server-side validation
- Secure file uploads
- Contact form rate limiting
- Proper authorization
- Safe handling of user-generated content

## Project Status

**Under Development**

This project is being built incrementally. Features and documentation will be updated as development progresses.

## Author

**Amir Abbas Afshar**

Full-Stack Developer | Backend-Focused

Specializing in PHP, Laravel, Database Design, and API Development.

- **GitHub:** [@AFEWSH](https://github.com/AFEWSH)
- **LinkedIn:** Add LinkedIn profile URL

## License

Copyright © Amir Abbas Afshar.

All rights reserved unless a separate license is added to this repository.
