# LIU Digital Yearbook — Technical Guide

## 1. Introduction

This document provides the technical setup and maintenance instructions for the **LIU Digital Yearbook**.

The project is a Laravel-based digital yearbook platform for managing academic years, graduates, graduation ceremonies, events, media, users, roles, audit records, and AI-assisted content generation.

This guide covers:

- System requirements
- Local development setup
- Environment configuration
- Database setup
- Storage and media configuration
- AI configuration
- Running the application
- Testing
- Production/deployment guidance
- Troubleshooting
- Project structure
- Database overview
- Security considerations

For administrator workflows and screenshots, see [`user-admin-guide.md`](./user-admin-guide.md).

---

## 2. Technology Stack

| Technology | Purpose |
|---|---|
| Laravel 13 | Backend application framework |
| PHP 8.3+ | Server-side runtime |
| MariaDB / MySQL | Production relational database |
| SQLite | Default/example and automated test database |
| Blade | Server-rendered views |
| Filament | Administrative interface components |
| Vite | Frontend asset bundling |
| Tailwind CSS | UI styling |
| Alpine.js | Client-side interactions |
| Lenis | Smooth scrolling/interactions |
| DomPDF | Graduate PDF generation |
| Pest / PHPUnit | Automated testing |
| Gemini API | AI-assisted text and vision features |
| Git / GitHub | Version control |

The repository currently requires PHP `^8.3` and Laravel `^13.8`. citeturn2file0

---

## 3. System Requirements

Before installing the project, make sure the development machine has:

- PHP 8.3 or newer
- Composer
- Node.js and npm
- MariaDB or MySQL for the main application database
- Git
- A web browser
- PHP extensions required by Laravel and the installed Composer packages

For Windows development, XAMPP can be used to provide Apache, PHP, and MariaDB/MySQL. The Laravel development server can also be used instead of Apache.

Verify the main tools:

```bash
php -v
composer --version
node -v
npm -v
git --version
```

Verify the database server separately using the database client's version command if required.

---

## 4. Clone the Repository

Clone the project from GitHub:

```bash
git clone https://github.com/sajajaber/yearbook.git
cd yearbook
```

If the repository has already been cloned:

```bash
git pull
```

Check the current branch and working tree:

```bash
git status
git branch
```

---

## 5. Install PHP Dependencies

Install Composer dependencies:

```bash
composer install
```

The project's Composer configuration includes Laravel, DomPDF, Tinker, and the development/test packages used by the application. citeturn2file0

If Composer reports a missing PHP extension, enable the required extension in the active `php.ini` and run the command again.

Confirm which PHP executable is being used on Windows:

```bash
where php
```

This is especially important when multiple PHP installations, such as XAMPP PHP and a standalone PHP installation, exist on the same machine.

---

## 6. Install Frontend Dependencies

Install Node.js dependencies:

```bash
npm install
```

The project uses Vite for asset building and includes Tailwind CSS, Alpine.js, Autoprefixer, Laravel's Vite plugin, and Lenis. citeturn3file0

For a production asset build:

```bash
npm run build
```

For frontend development with hot reloading:

```bash
npm run dev
```

---

## 7. Environment Configuration

Create the local environment file:

```bash
cp .env.example .env
```

On Windows PowerShell, if `cp` is unavailable:

```powershell
Copy-Item .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

The repository's example environment currently defaults to SQLite and defines the standard Laravel application, database, session, queue, cache, mail, storage, and Vite settings. citeturn4file0

### Recommended local application settings

For a MariaDB/MySQL development database, update the database section of `.env` to match the local database server:

```dotenv
APP_NAME="LIU Digital Yearbook"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yearbook_db
DB_USERNAME=yearbook_user
DB_PASSWORD=your_database_password
```

Do not commit the real `.env` file or API keys to Git.

---

## 8. Database Setup

### 8.1 Create the Database

Create the application database in MariaDB/MySQL, for example:

```sql
CREATE DATABASE yearbook_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Create or use an application database account with the required permissions, then configure the credentials in `.env`.

### 8.2 Run Migrations

Run:

```bash
php artisan migrate
```

For a fresh development database where all existing data can be discarded:

```bash
php artisan migrate:fresh
```

If the project seeders are required:

```bash
php artisan db:seed
```

Or, when appropriate during development:

```bash
php artisan migrate:fresh --seed
```

**Warning:** `migrate:fresh` deletes existing database tables and their data. Do not use it against a production database.

### 8.3 Database Testing

Automated tests are configured to use an in-memory SQLite database rather than the development MariaDB database. The PHPUnit configuration sets `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:` for tests. citeturn5file0

---

## 9. Database Structure

The principal application entities include:

- `users`
- `roles`
- `academic_years`
- `graduates`
- `majors`
- `schools`
- `campuses`
- `graduations`
- `events`
- `event_categories`
- `media`
- `hero_images`
- `ai_generations`
- `audit_logs`

Important relationships include:

- Users belong to roles.
- Graduates are associated with majors, campuses, schools, and graduation records.
- Academic years organize yearbook content.
- Events belong to event categories and can be associated with academic years, campuses, schools, and media.
- Graduate profiles can be associated with media and graduation information.
- AI generation records track generated content and its review state.
- Audit logs record relevant administrative activity.

The database ERD should be maintained alongside this guide when the schema changes.

---

## 10. Storage and Media

The application uses Laravel's filesystem abstraction.

The configured public disk stores files under:

```text
storage/app/public
```

and exposes them through:

```text
public/storage
```

The filesystem configuration defines the public disk and the `public/storage` symbolic link. citeturn8file0

Create the storage link after installation:

```bash
php artisan storage:link
```

### Media processing

Uploaded media can be processed by the application's image-processing services. Image handling includes thumbnail generation and profile/gallery image optimization where applicable.

If images are uploaded successfully but are not visible publicly, check:

1. The file exists under `storage/app/public`.
2. The `public/storage` symbolic link exists.
3. `FILESYSTEM_DISK` is configured correctly.
4. `APP_URL` is correct.
5. The web server has permission to read the storage directory.
6. Cached Laravel configuration has been cleared.

Clear configuration/cache when necessary:

```bash
php artisan optimize:clear
```

---

## 11. AI Configuration

The project includes Gemini-based AI services for text generation and vision-assisted functionality.

The application reads Gemini configuration from environment variables through `config/services.php`:

```dotenv
GEMINI_API_KEY=your_api_key
GEMINI_MODEL=gemini-3.8-flash
GEMINI_TIMEOUT=60
```

The current service configuration uses the Gemini model and timeout from these environment variables. fileciteturn7file2

The text-generation and vision services both obtain their configuration from `services.gemini`. fileciteturn7file0 fileciteturn7file1

### AI content workflow

AI-generated content is not intended to bypass the application's review workflow. Generated biographies and event summaries are stored as AI-generation records and can be reviewed, edited, approved, or rejected.

### AI troubleshooting

If AI requests fail:

1. Confirm `GEMINI_API_KEY` exists in `.env`.
2. Confirm the configured model is available to the API account.
3. Confirm the machine has outbound HTTPS access.
4. Check the configured timeout.
5. Run:

```bash
php artisan optimize:clear
```

6. Review Laravel logs in `storage/logs`.

Never commit the Gemini API key to GitHub.

---

## 12. Running the Application

### Option A — Laravel development server

Start Laravel:

```bash
php artisan serve
```

The application will normally be available at:

```text
http://127.0.0.1:8000
```

In a second terminal, run the frontend development server if needed:

```bash
npm run dev
```

### Option B — Composer development command

The project defines a Composer `dev` script that starts the Laravel server, queue listener, and Vite development process together. fileciteturn2file0

Run:

```bash
composer run dev
```

This is the preferred development shortcut when the local environment supports the required concurrent processes.

---

## 13. Queue Processing

The project uses a database-backed queue configuration by default in the example environment.

For local development, a queue worker can be started with:

```bash
php artisan queue:listen --tries=1 --timeout=0
```

For a conventional queue worker:

```bash
php artisan queue:work
```

When queued functionality is introduced into a deployment, the queue worker should be managed by an appropriate process supervisor rather than relying on an interactive terminal.

---

## 14. Authentication and Authorization

Administrative functionality is protected through Laravel authentication and role-based authorization.

The main roles are:

| Role | Responsibilities |
|---|---|
| **Admin** | Full management and configuration access |
| **Editor** | Create/edit content and submit drafts for review |
| **Reviewer** | Review, approve/reject, and publish content according to permissions |

The role system uses the application's role records and permission logic rather than relying only on UI visibility.

When modifying authorization:

- Protect routes with authentication middleware.
- Check the user's role/permission server-side.
- Do not rely on hiding a button as an authorization mechanism.
- Test unauthorized access through feature tests.

---

## 15. Content Publication Workflow

The main content workflow is:

```text
Draft
  │
  ▼
Review
  │
  ├──────────────► Rejected
  │
  ▼
Approved
  │
  ▼
Published
```

Typical statuses include draft, reviewed, approved, published, archived, and rejected, depending on the content type.

The workflow allows content to be prepared by an editor and then reviewed before public publication.

---

## 16. Public Routes

The public yearbook is grouped under `/yearbook`.

Typical routes include:

| Page | Route |
|---|---|
| Yearbook Archive | `/yearbook` |
| Graduates | `/yearbook/graduates` |
| Graduate Profile | `/yearbook/graduates/{id}` |
| Events | `/yearbook/events` |
| Event Details | `/yearbook/events/{id}` |
| Timeline | `/yearbook/timeline` |
| Graduate PDF | `/yearbook/graduates/{id}/pdf` |

Always treat `routes/web.php` as the authoritative source when documenting or changing routes.

---

## 17. PDF Generation

Graduate profiles can be exported as PDF documents.

The project includes `barryvdh/laravel-dompdf` as a Composer dependency. fileciteturn2file0

When PDF output is modified, verify:

- Graduate information renders correctly.
- Profile images resolve correctly.
- Long text wraps correctly.
- The generated document remains readable when printed.
- Public/private or consent-controlled information is not unintentionally included.

---

## 18. Testing

Run the complete automated test suite:

```bash
php artisan test
```

The project also exposes the Composer test script:

```bash
composer run test
```

The test configuration separates Unit and Feature tests and uses an in-memory SQLite database for test execution. fileciteturn5file0

### Recommended pre-commit checks

Before committing a significant change:

```bash
php artisan test
php artisan optimize:clear
npm run build
```

For code style where applicable:

```bash
./vendor/bin/pint
```

On Windows, the executable can be invoked through the project's vendor directory according to the local shell environment.

---

## 19. Production Build

Before deployment:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

Set production environment values in `.env`:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example
```

Use a production MariaDB/MySQL database and production-appropriate storage and mail configuration.

Do not expose:

- `.env`
- Database credentials
- API keys
- Application secrets
- Private uploaded files

### Web server

The web server document root should point to Laravel's `public/` directory, not the project root.

For example:

```text
/path/to/yearbook/public
```

This prevents application source files and configuration files from being directly served by the web server.

---

## 20. Deployment Checklist

Before declaring a deployment complete, verify:

### Application

- [ ] Production `.env` configured
- [ ] `APP_DEBUG=false`
- [ ] Correct `APP_URL`
- [ ] Application key configured
- [ ] Composer dependencies installed
- [ ] Frontend assets built

### Database

- [ ] Production database created
- [ ] Database credentials tested
- [ ] Migrations completed
- [ ] Required seed/configuration data loaded
- [ ] Database backups configured

### Storage

- [ ] Storage directories exist
- [ ] `php artisan storage:link` completed
- [ ] Uploaded media is readable
- [ ] File permissions are correct

### AI

- [ ] Gemini API key configured securely
- [ ] Gemini model configured
- [ ] API connectivity tested
- [ ] AI failures are logged

### Security

- [ ] Debug mode disabled
- [ ] HTTPS enabled
- [ ] `.env` is not publicly accessible
- [ ] Database credentials are not committed
- [ ] API keys are not committed
- [ ] Administrative routes require authorization

### Verification

- [ ] Public homepage loads
- [ ] Graduate directory works
- [ ] Graduate profiles work
- [ ] Events work
- [ ] Media gallery works
- [ ] Admin login works
- [ ] Content workflow works
- [ ] PDF generation works
- [ ] Automated tests pass

---

## 21. Cache and Optimization Commands

Useful Laravel commands during development and deployment include:

Clear cached configuration, routes, views, and other framework caches:

```bash
php artisan optimize:clear
```

Optimize the application for production:

```bash
php artisan optimize
```

If a configuration change appears to have no effect, clear the configuration cache before troubleshooting further.

---

## 22. Troubleshooting

### `vendor/autoload.php` is missing

Run:

```bash
composer install
```

### `php` uses the wrong installation

On Windows:

```bash
where php
```

Move the desired PHP installation earlier in the system PATH or explicitly use the correct PHP executable.

### OpenSSL errors in Composer

Check the PHP executable being used:

```bash
php --ini
php -m | findstr openssl
```

Make sure the OpenSSL extension is enabled in the active `php.ini` and that the PHP runtime dependencies match the installed PHP build.

### Database connection fails

Check:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yearbook_db
DB_USERNAME=yearbook_user
DB_PASSWORD=...
```

Then clear cached configuration:

```bash
php artisan optimize:clear
```

Confirm that MariaDB/MySQL is running.

### Images do not appear

Run:

```bash
php artisan storage:link
php artisan optimize:clear
```

Then verify that the expected file exists in `storage/app/public` and that the generated public URL points to the application's `/storage` path.

### Laravel displays stale configuration

Run:

```bash
php artisan optimize:clear
```

Then restart the Laravel server.

### AI requests fail or time out

Check:

- `GEMINI_API_KEY`
- `GEMINI_MODEL`
- `GEMINI_TIMEOUT`
- Internet/HTTPS access
- Laravel logs

Then run:

```bash
php artisan optimize:clear
```

### Tests fail because of stale state

Clear configuration and rerun:

```bash
php artisan optimize:clear
php artisan test
```

### Git reports unresolved merge/rebase conflicts

Check:

```bash
git status
```

Resolve the files listed as unmerged, stage them:

```bash
git add <resolved-file>
```

Then continue the operation required by Git, such as:

```bash
git rebase --continue
```

Do not discard local work without first checking the conflict carefully.

---

## 23. Recommended Development Workflow

A typical feature workflow is:

```text
Create branch
     │
     ▼
Implement change
     │
     ▼
Update migrations/models/controllers/views
     │
     ▼
Add or update tests
     │
     ▼
Run test suite
     │
     ▼
Build frontend assets
     │
     ▼
Review UI and permissions
     │
     ▼
Commit changes
     │
     ▼
Push branch
```

Example:

```bash
git checkout -b feature/example-change

# make changes

php artisan test
npm run build

git status
git add .
git commit -m "Add example change"
git push -u origin feature/example-change
```

---

## 24. Project Structure

The main application directories are:

```text
yearbook/
├── app/
│   ├── Contracts/
│   ├── Http/
│   ├── Models/
│   ├── Policies/
│   ├── Providers/
│   └── Services/
│
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
├── storage/
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── docs/
│   ├── technical-guide.md
│   └── user-admin-guide.md
│
├── .env.example
├── composer.json
├── package.json
├── phpunit.xml
└── README.md
```

The exact structure may grow as features are added. New architectural components should follow Laravel conventions and be reflected in this guide when they materially affect setup or maintenance.

---

## 25. Security Guidelines

### Environment variables

Keep secrets in `.env`:

- Database passwords
- API keys
- Mail credentials
- Cloud storage credentials
- Application secrets

### Authorization

Always enforce authorization on the server side. A hidden button is not a security control.

### File uploads

Validate uploaded files before storage and use the application's media-processing services rather than trusting client-provided filenames or MIME types.

### Public graduate information

Graduate content should only become publicly available according to the configured consent and publication workflow.

### Production errors

Do not run production with:

```dotenv
APP_DEBUG=true
```

Production errors should be logged without exposing application internals to visitors.

---

## 26. Documentation Maintenance

Update this technical guide whenever a change affects:

- Installation requirements
- PHP or Laravel versions
- Composer dependencies
- Node dependencies
- Environment variables
- Database setup
- Storage configuration
- AI configuration
- Deployment procedure
- Testing commands
- Authentication/authorization architecture
- Important troubleshooting procedures

For changes to administrative workflows, update [`user-admin-guide.md`](./user-admin-guide.md) instead.

For high-level project information, update the root [`README.md`](../README.md).

---

## 27. Related Documentation

- [`README.md`](../README.md) — project overview and quick start
- [`user-admin-guide.md`](./user-admin-guide.md) — administrator/editor/reviewer instructions and screenshots
- Database migrations in [`database/migrations`](../database/migrations) — authoritative database schema
- Application routes in [`routes`](../routes) — authoritative route definitions
- Composer configuration in [`composer.json`](../composer.json) — PHP dependencies and project scripts
- Frontend configuration in [`package.json`](../package.json) — JavaScript dependencies and Vite scripts

---

## 28. Repository

GitHub repository:

https://github.com/sajajaber/yearbook

---

**Document purpose:** Technical setup, configuration, maintenance, testing, and deployment reference for the LIU Digital Yearbook.
