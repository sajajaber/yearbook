# LIU Digital Yearbook

A web-based digital yearbook platform for documenting and presenting university graduates, graduation ceremonies, events, and media in an organized, searchable, and visually engaging archive.

The platform provides a public-facing digital yearbook together with a role-based administration system for managing graduates, events, academic years, media, and publication workflows. It also integrates AI-powered features to assist with content creation, media metadata, and semantic discovery.

---

## Overview

The LIU Digital Yearbook is built as a Laravel web application that combines:

- A public digital yearbook
- Graduate profiles and directories
- Graduation ceremony pages
- Event management and galleries
- A centralized media library
- Academic-year organization
- Role-based administration
- Editorial review and publication workflows
- AI-assisted content generation
- AI-powered media caption and tag suggestions
- Semantic search
- Graduate PDF profiles
- Accessibility and structured media metadata

The system is designed to support the complete lifecycle of yearbook content, from creation and media management through review, approval, and public publication.

---

## Main Features

### Public Digital Yearbook

Visitors can explore published yearbook content through:

- Academic-year archive
- Graduate directory
- Graduate profile pages
- Graduation ceremony pages
- Events
- Event galleries
- Timeline
- Featured events
- Media galleries
- Semantic search
- Graduate PDF profiles
- QR-ready profile sharing

Only content that has reached the appropriate publication state is exposed through the public yearbook.

---

### Graduate Management

Administrators and editors can manage graduate records containing information such as:

- Name
- Student reference
- Portrait
- Degree level
- Major
- School
- Campus
- Graduation
- Biography/profile text
- Achievements
- Activities
- Projects
- Internships
- Future plans
- Quote
- Resume/CV
- Consent status
- Publication status

Graduate portraits are processed for appropriate display, including thumbnail generation where supported.

Graduate profiles can also be exported as PDF documents.

---

### Event Management

The platform supports complete event management, including:

- Event title
- Event category
- Event date
- Location
- Description
- Featured status
- Academic-year association
- Campus association
- School association
- Event media
- Publication workflow

Events can move through editorial states before becoming publicly visible.

Featured events displayed on the public homepage are associated with the active academic year.

---

### Graduation Management

Graduation ceremonies can be organized and presented as dedicated public pages.

Graduation content can include:

- Ceremony information
- Academic-year context
- Associated graduates
- Media galleries
- Graduation-related content
- Public graduate discovery

---

### Media Library

The media library provides centralized management of uploaded media.

Supported media types include:

- Images
- Videos
- Documents

Media records support metadata such as:

- File name
- Caption
- Alternative text
- Credit
- Tags
- File type
- Storage path
- Thumbnail path
- Checksum

The system generates thumbnails for supported images and uses media checksums to assist with media identification.

---

## AI Integration

The LIU Digital Yearbook integrates AI across content generation, media management, and content discovery. These features are designed to assist administrators and editors while keeping human review and editorial control in the workflow.

### AI-Powered Features

| AI Feature | Purpose |
|---|---|
| **Semantic Search** | Understands natural-language search queries and retrieves relevant yearbook content based on semantic meaning rather than only exact keyword matches. |
| **Graduate Biography Generation** | Generates draft biography/profile content for graduates based on available graduate information. |
| **Event Summary Generation** | Generates draft summaries for events from their available information. |
| **Media Caption Generation** | Generates descriptive captions for uploaded media to help administrators and editors complete media metadata. |
| **Media Tag Generation** | Generates relevant tags for uploaded media to improve organization and discoverability within the media library. |

---

## Role-Based Access Control

The administration system uses role-based access control to separate responsibilities.

### Admin

The Admin role provides full administrative access, including:

- Managing users
- Managing roles
- Managing academic years
- Managing campuses
- Managing schools
- Managing majors
- Managing event categories
- Managing graduates
- Managing events
- Managing graduations
- Managing media
- Reviewing content
- Approving or rejecting content
- Publishing content
- Managing system configuration

---

### Editor

Editors are responsible for preparing and maintaining yearbook content.

Typical Editor responsibilities include:

- Creating graduates
- Editing graduate information
- Creating events
- Editing events
- Managing media
- Preparing content for review
- Submitting content through the publication workflow

Editors do not have the same approval and publication authority as reviewers/admins.

---

### Reviewer

Reviewers are responsible for editorial review.

They can:

- Review submitted content
- Add review notes
- Approve content
- Reject content
- Publish approved content where permitted

The review process separates content preparation from editorial approval.

---

## Content Publication Workflow

The Yearbook uses a structured content workflow rather than immediately publishing every newly created record.

A typical workflow is:

```text
Draft
  ↓
Submitted for Review
  ↓
Reviewed
  ↓
Approved
  ↓
Published
```

Content may also be rejected or archived depending on its state.

This workflow helps ensure that public yearbook content is reviewed before publication.

---

## Academic Years

Yearbook content is organized around academic years.

Academic years contain information such as:

- Title
- Start date
- End date
- Status
- Dedication

Academic-year states include:

- Draft
- Active
- Archived

The active academic year is used by the public yearbook for current-year content such as featured events.

---

## Technology Stack

| Technology | Purpose |
|---|---|
| Laravel | Backend web framework |
| PHP | Application runtime |
| Blade | Server-rendered UI |
| Eloquent ORM | Database interaction |
| MySQL / MariaDB | Development database |
| SQLite | Automated test database |
| Vite | Frontend asset bundling |
| Tailwind CSS | Utility-first styling |
| Alpine.js | Frontend interactions |
| JavaScript | Client-side functionality |
| Gemini AI | AI-powered content and media assistance |
| DOMPDF | Graduate and Yearbook PDF generation |
| Pest / PHPUnit | Automated testing |

The application uses Laravel's native application architecture and a custom Blade-based administration interface.

---

## System Architecture

At a high level, the application follows a Laravel MVC architecture:

```text
                    ┌─────────────────────┐
                    │    Public Users     │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │   Public Routes     │
                    │   & Controllers     │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │  Yearbook Services  │
                    │  Search / AI / PDF  │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │   Eloquent Models   │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │ MySQL / MariaDB DB  │
                    └─────────────────────┘


                    ┌─────────────────────┐
                    │ Admin / Editor /    │
                    │ Reviewer / Admin UI │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │ Authentication &    │
                    │ Role Middleware     │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │ Admin Controllers   │
                    │ & Blade Views       │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │ Database / Media /  │
                    │ AI Services         │
                    └─────────────────────┘
```

---

## Main Database Entities

The application's core data model includes:

- Users
- Roles
- Academic Years
- Graduates
- Majors
- Schools
- Campuses
- Graduations
- Events
- Event Categories
- Media
- Hero Images
- AI Generations
- Audit Logs

The relationships between these entities support graduate organization, event management, academic-year archives, media galleries, user permissions, AI generation tracking, and auditability.

For the detailed database structure and relationships, see the technical documentation.

---

## Public Routes

The public yearbook is available through routes including:

| Route | Purpose |
|---|---|
| `/yearbook` | Yearbook archive/home |
| `/yearbook/graduates` | Graduate directory |
| `/yearbook/graduates/{id}` | Graduate profile |
| `/yearbook/graduates/{id}/pdf` | Graduate PDF |
| `/yearbook/events` | Event directory |
| `/yearbook/events/{id}` | Event details |
| `/yearbook/timeline` | Yearbook timeline |

---

## Search

The public yearbook provides search functionality through the application's semantic search service.

Search results are designed to help visitors discover yearbook content using natural-language queries rather than requiring exact database values.

---

## Media and Accessibility

Media records support accessibility-oriented metadata, including alternative text.

The system also processes image thumbnails to provide optimized versions of supported media for use throughout the yearbook.

Media can be associated with different yearbook entities, including events and graduates.

---

## PDF Generation

Published graduate profiles can be generated as PDF documents.
Yearbooks can be generated as PDF documents.

The PDF functionality provides a printable/shareable representation of the graduate's public profile and for the Yearbook.

---

## Installation

### Requirements

Before installing the project, make sure the environment includes:

- PHP 8.3 or higher
- Composer
- Node.js and npm
- MySQL or MariaDB
- Git

### Clone the repository

```bash
git clone <repository-url>
cd yearbook
```

### Install PHP dependencies

```bash
composer install
```

### Install frontend dependencies

```bash
npm install
```

### Configure the environment

```bash
cp .env.example .env
php artisan key:generate
```

Configure the database connection in `.env`.

Example:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yearbook_db
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### Run migrations and seeders

```bash
php artisan migrate --seed
```

### Create the storage link

```bash
php artisan storage:link
```

### Build frontend assets

```bash
npm run build
```

### Start the application

```bash
php artisan serve
```

For the complete environment and deployment procedure, see:

- [`docs/technical-guide.md`](docs/technical-guide.md)
- [`docs/user-admin-guide.md`](docs/user-admin-guide.md)

---

## Development

For local development, the project can run Laravel and Vite together using the configured development scripts.

```bash
composer run dev
```

Frontend assets can also be started separately:

```bash
npm run dev
```

---

## Testing

The project includes automated tests covering application functionality.

Run the test suite with:

```bash
php artisan test
```

or:

```bash
composer test
```

The test environment uses SQLite for isolated automated testing.

---

## Project Structure

Important project directories include:

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
├── Services/
└── Providers/

config/
database/
├── migrations/
├── seeders/
└── factories/

resources/
├── css/
├── js/
└── views/

routes/
├── web.php
└── console.php

storage/
tests/
├── Feature/
└── Unit/

docs/
├── technical-guide.md
└── user-admin-guide.md
```

---

## Security

The application uses Laravel's authentication and authorization mechanisms to protect administrative functionality.

Important security practices include:

- Role-based access control
- Authentication for administrative routes
- Validation through Laravel Form Requests
- CSRF protection
- Server-side authorization
- Controlled file uploads
- Environment-based API credentials
- Protected storage
- Audit logging

API keys and other sensitive configuration values should never be committed to the repository.

---

## Documentation

Project documentation is organized into the following guides:

### Technical Guide

[`docs/technical-guide.md`](docs/technical-guide.md)

Covers:

- Installation
- Environment configuration
- Database setup
- Storage
- AI configuration
- Running the application
- Testing
- Deployment
- Troubleshooting
- Maintenance

### User/Admin Guide

[`docs/user-admin-guide.md`](docs/user-admin-guide.md)

Covers:

- Administrator tasks
- Editor tasks
- Reviewer tasks
- Graduate management
- Event management
- Media management
- Review and publication workflows
- AI-assisted media features
- Academic-year management

---

## Development Workflow

A typical content-management workflow is:

```text
Create Content
      ↓
Add / Edit Media
      ↓
Complete Metadata
      ↓
Submit for Review
      ↓
Reviewer Checks Content
      ↓
Approve / Reject
      ↓
Publish
      ↓
Public Yearbook
```

AI-generated content is treated as assistance rather than automatically trusted final content. Generated content can be reviewed and edited before publication.

---

## Project Purpose

The LIU Digital Yearbook provides a centralized digital platform for preserving and presenting university graduate and event information.

The system combines traditional yearbook content with modern web technologies, structured content management, searchable archives, multimedia galleries, automated PDF profiles, role-based editorial workflows, and AI-assisted functionality.

The result is a maintainable digital archive that can be updated across academic years while providing visitors with an accessible and engaging way to explore university memories and achievements.
