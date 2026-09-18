# LIU Digital Yearbook — Technical Guide

## 1. Introduction

This guide documents the setup, configuration, development, testing, and deployment of the **LIU Digital Yearbook** Laravel application.

The application provides a public digital yearbook and an authenticated administration system for graduates, events, graduations, media, academic years, users, review workflows, audit records, and AI-assisted features.

> **Source of truth:** When this guide conflicts with the repository, the current code in `composer.json`, `package.json`, `.env.example`, `phpunit.xml`, `routes/web.php`, configuration files, migrations, and application code takes precedence.

---

## 2. Current Technology Stack

| Technology | Current project usage |
|---|---|
| Laravel | 13.x (`^13.8`) |
| PHP | 8.3+ (`^8.3`) |
| Blade | Server-rendered application UI |
| Eloquent ORM | Database access and relationships |
| MySQL / MariaDB | Supported relational database for the application |
| SQLite | Default `.env.example` database and automated test database |
| Vite | Frontend asset development/build |
| Tailwind CSS | Styling |
| Alpine.js | Client-side interactions |
| Lenis | Frontend scrolling interactions |
| Gemini API | AI-assisted generation and media-related assistance |
| barryvdh/laravel-dompdf | PDF generation |
| Pest / PHPUnit | Automated testing |
| Laravel Breeze | Authentication scaffolding/dependency |
| Laravel Pint | PHP code formatting |

The project does **not** use Filament. The administration interface is implemented with the application's Laravel controllers and Blade views.

---

## 3. System Requirements

Install the following before setting up the project:

- PHP 8.3 or newer
- Composer
- Node.js and npm
- MySQL or MariaDB if using the relational development database
- Git
- A modern web browser

Check the installed tools:

```bash
php -v
composer --version
node -v
npm -v
git --version
```

On Windows, if multiple PHP installations are installed, verify the active executable with:

```bash
where php
```

XAMPP can be used for local PHP and MySQL/MariaDB services, but the application itself can also be run with Laravel's development server.

---

## 4. Clone and Install

Clone the repository:

```bash
git clone https://github.com/sajajaber/yearbook.git
cd yearbook
```

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

Create the environment file:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

---

## 5. Environment Configuration

The repository's `.env.example` currently uses SQLite by default:

```dotenv
DB_CONNECTION=sqlite
```

It also configures database-backed sessions, queues, and cache by default:

```dotenv
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=local
```

### MySQL / MariaDB configuration

For the project's common MySQL/MariaDB development setup, change the database section in `.env` to match the local database server:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yearbook_db
DB_USERNAME=yearbook_user
DB_PASSWORD=your_database_password
```

The application also has a dedicated `mariadb` connection available through Laravel's database configuration.

Never commit `.env`, database passwords, or API keys.

---

## 6. Database Setup

Create the database if using MySQL/MariaDB:

```sql
CREATE DATABASE yearbook_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Then configure the credentials in `.env` and run:

```bash
php artisan migrate
```

The project includes seeders for the application's initial/reference data. To run all configured seeders:

```bash
php artisan db:seed
```

For a disposable development database:

```bash
php artisan migrate:fresh --seed
```

> **Warning:** `migrate:fresh` deletes the existing database tables and data. Never run it against a database containing data that must be preserved.

The main seeded data includes roles, schools, majors, users, campuses, academic years, event categories, graduations, graduates, events, and media.

### Automated test database

Tests use an in-memory SQLite database. `phpunit.xml` sets:

```text
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

The test environment also uses synchronous queues, array cache/session storage, and an array mail driver.

---

## 7. Storage and Media

Laravel's filesystem configuration defines:

- `local` disk: `storage/app/private`
- `public` disk: `storage/app/public`
- `public/storage` symbolic link → `storage/app/public`

Create the public storage link after installation:

```bash
php artisan storage:link
```

The application uses the public disk for media that must be displayed through the public yearbook.

### Media processing

The application contains media/image-processing functionality for uploaded content. Image handling can include thumbnail generation and profile/gallery image processing where supported.

If an uploaded image does not appear:

1. Confirm the file exists under `storage/app/public`.
2. Confirm `public/storage` exists as the Laravel storage link.
3. Confirm `FILESYSTEM_DISK` is correct.
4. Confirm `APP_URL` is correct.
5. Run:

```bash
php artisan optimize:clear
```

---

## 8. Frontend Development

The project uses Vite.

Start the Vite development server:

```bash
npm run dev
```

Build production frontend assets:

```bash
npm run build
```

The project also defines a Composer development command that starts the Laravel server, database queue listener, and Vite process together:

```bash
composer run dev
```

The Composer `dev` script uses `concurrently` to run these processes together.

---

## 9. AI Configuration

The application integrates Gemini through the application's AI services.

Configure the following environment variables in `.env`:

```dotenv
GEMINI_API_KEY=your_api_key
GEMINI_MODEL=gemini-3.8-flash
GEMINI_TIMEOUT=60
```

These values are read from `config/services.php`.

AI functionality in the application includes:

- Semantic search
- Graduate biography generation
- Event summary generation
- Media caption generation
- Media tag generation/suggestions

AI-generated content is treated as assisted content rather than an automatic replacement for editorial review. AI generation records are stored in the application for review/status tracking.

If AI requests fail:

1. Check `GEMINI_API_KEY`.
2. Check `GEMINI_MODEL`.
3. Check `GEMINI_TIMEOUT`.
4. Confirm outbound HTTPS access.
5. Clear cached configuration:

```bash
php artisan optimize:clear
```

6. Check Laravel logs in `storage/logs`.

Never commit the Gemini API key.

---

## 10. Running the Application

### Laravel server

Run:

```bash
php artisan serve
```

The local application is normally available at:

```text
http://127.0.0.1:8000
```

### Full development environment

Alternatively:

```bash
composer run dev
```

This starts the Laravel development server, queue listener, and Vite development server together.

---

## 11. Queue Processing

The default example environment uses the database queue connection.

The development Composer script starts:

```bash
php artisan queue:listen --tries=1 --timeout=0
```

A conventional worker can also be started with:

```bash
php artisan queue:work
```

If the application is deployed with queued jobs, the worker should be kept running by the deployment/process-management system.

---

## 12. Authentication and Role-Based Access

Authenticated administration is protected through Laravel authentication and the project's `role` middleware.

The application uses three main roles:

| Role | Main responsibility |
|---|---|
| Admin | Full administration, configuration, content management, review, approval, and publication |
| Editor | Create/edit content and submit content for review |
| Reviewer | Review content, add review feedback, approve/reject, and publish where permitted |

Authorization is enforced server-side through route middleware and controller logic. UI visibility alone is not considered an authorization mechanism.

The current route groups use combinations of `auth`, `verified`, and role middleware such as `role:admin`, `role:admin,editor`, and `role:admin,editor,reviewer`.

---

## 13. Content Publication Workflow

The application's publishable content uses workflow states such as:

```text
Draft
  ↓
Review
  ├──→ Rejected / Changes Requested
  ↓
Approved
  ↓
Published
  ↓
Archived
```

The exact available states depend on the content type. Graduates and events support draft/review/approval/publication states, including rejected content.

Editors prepare and submit content. Reviewers and administrators handle review actions. Public pages only expose content that satisfies the application's publication and consent rules.

---

## 14. Public Routes

The current public yearbook routes are defined in `routes/web.php`.

| Purpose | Route |
|---|---|
| Home | `/` |
| Yearbook archive | `/archive` |
| Graduate directory | `/graduates` |
| Graduate profile | `/graduates/{student_reference}` |
| Graduate resume | `/graduates/{student_reference}/resume` |
| Graduate PDF | `/graduates/{student_reference}/pdf` |
| Events | `/events` |
| Event details | `/events/{id}` |
| Graduations | `/graduations` |
| Graduation details | `/graduations/{id}` |
| Timeline | `/timeline` |
| Academic-year page | `/{academicYear}` |
| Academic-year PDF | `/{academicYear}/pdf` |
| Search page | `/search` |

### Graduate URL identifier

The public graduate profile uses the graduate's **student reference**, not the database primary-key ID or a generated UUID. The public routes are hosted at the domain root rather than under a `/yearbook` prefix.

Example:

```text
/graduates/STU-2026-015
```

The same `student_reference` parameter is used for the public graduate resume and PDF routes.

---

## 15. Administrative Routes

Authenticated administration is implemented through `routes/web.php` and includes management for:

- Academic years
- Majors
- Campuses
- Schools
- Event categories
- Graduations
- Media
- Events
- Graduates
- Graduate imports
- Users
- Audit logs
- Reviews
- Review feedback
- Notifications
- AI generations
- Profile settings
- Hero images/system settings

Role middleware determines which administrative functions each role can access.

---

## 16. Database Overview

The main database entities represented by the application include:

- Users
- Roles
- Academic years
- Graduates
- Majors
- Schools
- Campuses
- Graduations
- Events
- Event categories
- Media
- Hero images
- AI generations
- Audit logs
- Review feedback
- Notifications

Important relationships include:

- Users belong to roles.
- Graduates reference academic/organizational entities such as majors, campuses, schools, and graduations.
- Graduates have a unique student reference used by the public profile URL.
- Events can be associated with academic years, categories, campuses, schools, and media.
- Media can be associated with graduates and events.
- AI generation records track generated content and review status.
- Review feedback supports the editorial review process.
- Notifications support administrative workflow communication.

For the authoritative schema, use the migrations under `database/migrations` and the Eloquent relationships in `app/Models`.

---

## 17. PDF Generation

The project uses `barryvdh/laravel-dompdf` for PDF generation.

Public PDF functionality includes:

- Graduate profile PDFs
- Academic-year/yearbook PDFs

When changing PDF output, verify that:

- Graduate/profile information is correct.
- Images resolve correctly.
- Long content wraps correctly.
- Consent/publication rules are respected.
- The resulting document remains readable when printed or shared.

---

## 18. Testing

Run the complete test suite:

```bash
php artisan test
```

The Composer test script is:

```bash
composer run test
```

PHPUnit/Pest tests are organized into:

```text
tests/
├── Feature/
└── Unit/
```

The configured test environment uses in-memory SQLite and synchronous queues.

### Recommended checks

Before committing a significant change:

```bash
php artisan test
npm run build
```

For PHP formatting:

```bash
./vendor/bin/pint
```

---

## 19. Production Deployment

For a production deployment, install PHP dependencies without development packages:

```bash
composer install --no-dev --optimize-autoloader
```

Install/build frontend assets:

```bash
npm ci
npm run build
```

Configure production environment values, including:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example
```

Run database migrations:

```bash
php artisan migrate --force
```

Create the public storage link:

```bash
php artisan storage:link
```

Optimize Laravel configuration:

```bash
php artisan optimize
```

The web server should point its document root to the project's `public/` directory rather than the project root.

If the database queue is used in production, a queue worker must be kept running by the server/process manager.

---

## 20. Deployment Checklist

### Application

- [ ] Production `.env` configured
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` configured
- [ ] `APP_KEY` configured
- [ ] Composer production dependencies installed
- [ ] Frontend assets built

### Database

- [ ] Production database created
- [ ] Credentials tested
- [ ] Migrations completed
- [ ] Required seed/reference data loaded where applicable
- [ ] Backups configured

### Storage

- [ ] `php artisan storage:link` completed
- [ ] Uploaded media is accessible where intended
- [ ] Storage permissions are correct

### AI

- [ ] Gemini API key configured securely
- [ ] Gemini model configured
- [ ] API connectivity tested

### Security

- [ ] `APP_DEBUG=false`
- [ ] HTTPS configured
- [ ] `.env` protected
- [ ] Database credentials not committed
- [ ] Gemini API key not committed
- [ ] Administrative routes protected by authentication/authorization

### Functional verification

- [ ] Public homepage loads
- [ ] Graduate directory works
- [ ] Graduate profile URLs use `student_reference`
- [ ] Graduate resume/PDF routes work
- [ ] Events and galleries work
- [ ] Graduation pages work
- [ ] Admin authentication works
- [ ] Review/publication workflow works
- [ ] Media uploads work
- [ ] AI features work when configured
- [ ] Automated tests pass

---

## 21. Useful Maintenance Commands

Clear cached configuration, routes, views, and framework caches:

```bash
php artisan optimize:clear
```

Optimize the application:

```bash
php artisan optimize
```

Create/update the public storage link:

```bash
php artisan storage:link
```

Run migrations:

```bash
php artisan migrate
```

Run tests:

```bash
php artisan test
```

---

## 22. Troubleshooting

### `vendor/autoload.php` is missing

Run:

```bash
composer install
```

### The wrong PHP installation is being used on Windows

Run:

```bash
where php
php --ini
```

Make sure the intended PHP installation is first in the PATH and that its `php.ini` contains the required extensions.

### Composer/OpenSSL errors

Check:

```bash
php --ini
php -m | findstr openssl
```

The OpenSSL extension must be available to the PHP executable used by Composer.

### Database connection errors

Verify `.env`, confirm the database server is running, then run:

```bash
php artisan optimize:clear
php artisan migrate:status
```

### Images/media are not displayed

Run:

```bash
php artisan storage:link
php artisan optimize:clear
```

Then verify the media file exists under `storage/app/public` and that the public storage link is valid.

### AI requests fail or time out

Check:

- `GEMINI_API_KEY`
- `GEMINI_MODEL`
- `GEMINI_TIMEOUT`
- Internet/HTTPS connectivity
- Laravel logs in `storage/logs`

Then run:

```bash
php artisan optimize:clear
```

### Tests fail unexpectedly

Clear cached configuration and rerun:

```bash
php artisan optimize:clear
php artisan test
```

### Git reports merge/rebase conflicts

Check the repository state:

```bash
git status
```

Resolve the reported files, stage the resolved files, and then continue the Git operation requested by Git, such as:

```bash
git add <resolved-file>
git rebase --continue
```

Do not use a force push to solve an ordinary non-fast-forward update unless the repository history is intentionally being rewritten.

---

## 23. Project Structure

The current project is organized into the following main folders:

```text
yearbook/
├── app/
│   ├── Contracts/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   ├── Notifications/
│   ├── Providers/
│   ├── Services/
│   ├── Support/
│   └── View/
│
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
├── storage/
│   ├── app/
│   │   ├── private/
│   │   └── public/
│   ├── framework/
│   └── logs/
├── tests/
│   ├── Feature/
│   └── Unit/
├── docs/
└── vendor/
```

This section intentionally shows folders rather than individual source files.

---

## 24. Security Guidelines

### Environment secrets

Keep credentials and secrets in `.env`, including:

- Database passwords
- Gemini API keys
- Mail credentials
- Cloud storage credentials if configured

### Authorization

Authorization must be enforced server-side through middleware and application logic. Hiding an interface element is not sufficient protection.

### File uploads

Uploaded files should pass the application's validation and media-processing flow. Do not trust client-provided filenames or metadata.

### Graduate privacy

Graduate content is subject to publication and consent rules. Public graduate profiles require the appropriate publication and consent state enforced by the application.

### Production configuration

Do not expose debug information in production:

```dotenv
APP_DEBUG=false
```

The production web server should expose Laravel's `public/` directory, not the repository root.

---

## 25. Documentation Maintenance

Update this guide when changes affect:

- PHP/Laravel requirements
- Composer or npm dependencies
- Environment variables
- Database configuration or migrations
- Storage/media configuration
- AI configuration
- Routes
- Authentication/authorization
- Testing commands
- Deployment procedure
- Project structure

For high-level project information, update `README.md`.

For database relationships, keep the ERD/documentation synchronized with the migrations and Eloquent models.

---

## 26. Repository

GitHub repository:

https://github.com/sajajaber/yearbook

---

**Document purpose:** Technical setup, configuration, maintenance, testing, and deployment reference for the LIU Digital Yearbook.