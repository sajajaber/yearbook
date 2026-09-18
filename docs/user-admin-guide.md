# User & Admin Guide

## 1. Introduction

The LIU Digital Yearbook provides a public yearbook experience and an authenticated administration area for managing graduates, events, graduations, media, academic years, and related content.

This guide explains the main tasks available to authenticated users and the content publication workflow used by the system.

---

## 2. Accessing the Administration Area

Open the application and sign in using an authorized account.

After authentication, users with the `admin`, `editor`, or `reviewer` role can access the dashboard. Access to individual functions is controlled by role-based permissions.

### Main roles

| Role | Main responsibilities |
|---|---|
| **Admin** | Full administration, user management, settings, audit logs, content management, review actions, and publishing |
| **Editor** | Create and edit content, upload/manage media, generate supported AI content, and submit content for review |
| **Reviewer** | Review submitted content, approve it, request changes with feedback, and publish approved content |

A user without the required role receives an authorization error when attempting to access a restricted area.

---

## 3. Dashboard

The dashboard is available to authenticated Admins, Editors, and Reviewers.

Use the dashboard as the starting point for administrative work and to access the areas available to the current role.

---

## 4. Managing Graduates

### 4.1 View the Graduate Directory

1. Sign in.
2. Open **Graduates**.
3. Browse the graduate records.
4. Use the available filters and search options to narrow the list.

The graduate administration area supports filtering by:

- Publication status
- Academic year
- Campus
- School
- Major
- Search text, including graduate name and student reference

Reviewers do not see draft graduate records in the graduate administration list.

### 4.2 Add a Graduate

Admins and Editors can create graduate records.

1. Open **Graduates**.
2. Select **Add Graduate**.
3. Enter the graduate information.
4. Select the relevant school, major, campus, and graduation information.
5. Add the profile information and other available fields.
6. Upload a profile photo if available.
7. Optionally upload a resume/CV.
8. Add associated media if required.
9. Save the graduate record.

The profile photo is processed by the application before being stored. Resume files are stored as document media.

### 4.3 Edit a Graduate

1. Open **Graduates**.
2. Locate the graduate.
3. Select **Edit**.
4. Update the required information.
5. Save the changes.

When an existing graduate has open review feedback, updating the record resolves the open feedback associated with that graduate.

### 4.4 Generate an AI Biography

Admins and Editors can request an AI-generated biography from the graduate editing workflow.

1. Open the graduate's **Edit** page.
2. Select the AI biography generation action.
3. Wait for the generation request to complete.
4. The generated text is stored as an AI generation with a **pending review** status.
5. A Reviewer or Admin can review the generated content before it is treated as approved AI content.

AI-generated text should be reviewed before being used as final public content.

### 4.5 Submit a Graduate for Review

When the graduate record is ready:

1. Open the graduate list.
2. Locate the graduate.
3. Select **Submit for Review**.
4. The graduate moves into the review workflow.
5. Reviewers are notified that the graduate is ready for review.

Editors can submit graduate records for review. Admins can also perform the workflow actions available to them.

---

## 5. Reviewing a Graduate

Reviewers and Admins can review graduate records.

### Approve

1. Open the graduate review page.
2. Check the profile information, media, consent status, and other relevant content.
3. Select **Approve** when the record is ready.

The graduate moves to the approved state and the relevant editor is notified.

### Request Changes

If changes are required:

1. Open the graduate review page.
2. Select **Request Changes**.
3. Enter a clear review note explaining what needs to be corrected.
4. Submit the feedback.

The graduate is returned for changes, the feedback is stored, and the relevant editor is notified.

### Publish

After approval, an authorized Reviewer or Admin can select **Publish**.

A graduate must satisfy the application's publication and consent rules before the public profile becomes available.

---

## 6. Managing Events

### 6.1 Add an Event

Admins and Editors can create events.

1. Open **Events**.
2. Select **Add Event**.
3. Enter the event title, date, location, description, and academic year.
4. Select the event category.
5. Select the applicable campuses and schools.
6. Mark the event as **Featured** if it should be eligible for featured-event display.
7. Attach available media.
8. Save the event.

### 6.2 Edit an Event

1. Open **Events**.
2. Locate the event.
3. Select **Edit**.
4. Update the information.
5. Save the changes.

Open review feedback associated with the event is resolved when the event is updated.

### 6.3 Generate an AI Event Summary

Admins and Editors can generate an AI summary from the event workflow.

1. Open the event.
2. Select the AI summary action.
3. The generated summary is saved as an AI generation with **pending review** status.
4. A Reviewer or Admin can review the generated content.

### 6.4 Submit an Event for Review

1. Open the event list.
2. Locate the event.
3. Select **Submit for Review**.
4. Reviewers receive a notification.

### 6.5 Review an Event

Reviewers and Admins can:

- Approve the event
- Request changes and provide a review note
- Publish the event after approval

The same review workflow used for graduate profiles is applied to events.

---

## 7. Managing Graduations

Admins and Editors can manage graduation records.

Typical tasks include:

1. Open **Graduations**.
2. Create or edit a graduation record.
3. Associate the graduation with the relevant academic year.
4. Add the available graduation information and media.
5. Save the record.

Graduation pages are exposed through the public yearbook when the associated content meets the application's publication rules.

---

## 8. Managing Media

### 8.1 Media Library

Admins, Editors, and Reviewers can access the media library. Admins and Editors can create and manage media.

The media library can be used for images and documents associated with yearbook content.

### 8.2 Upload Media

1. Open **Media**.
2. Select the upload/add action.
3. Choose the file.
4. Enter available metadata such as caption, alternative text, credit, and tags.
5. Save the media.

Image media may have a generated thumbnail. Graduate portrait images are processed specifically for profile display.

### 8.3 AI Media Suggestions

Admins and Editors can use the available AI media actions to assist with:

- Captions
- Tags
- Tag suggestions

AI-generated media metadata should be checked before relying on it as final editorial metadata.

---

## 9. Academic Years

Admins and Editors can manage academic years.

Academic years have a status such as:

- Draft
- Active
- Archived

The active academic year is important to public yearbook discovery. For example, featured events displayed on the homepage are associated with the active academic year rather than being selected from every academic year.

### Recommended workflow

1. Create the academic year.
2. Enter its title and date range.
3. Add the dedication if required.
4. Set the appropriate status.
5. Make the intended year active when it should become the current public yearbook year.
6. Archive older years when appropriate.

Avoid having multiple years unintentionally configured as the current active year.

---

## 10. Schools, Campuses, Majors, and Event Categories

Admins and Editors can manage the supporting data used by yearbook content:

- **Schools**
- **Campuses**
- **Majors**
- **Event Categories**

These records are used when creating and filtering graduates and events.

Before deleting or changing supporting data, check whether it is already associated with existing content.

---

## 11. Users and Administration

User management is restricted to Admins.

### Manage Users

1. Open **Users**.
2. Create or edit a user.
3. Assign the appropriate role.
4. Save the account.

Available application roles are **Admin**, **Editor**, and **Reviewer**.

### Settings

Admins can access the Settings area for administrative configuration, including hero-image management.

### Audit Logs

Admins can access **Audit Logs** to review recorded system actions such as content creation, updates, deletion, review actions, publishing, and relevant AI-generation events.

---

## 12. Notifications

The application sends workflow notifications for important review events.

Examples include:

- A graduate submitted for review
- An event submitted for review
- A graduate approved
- An event approved
- Changes requested on a graduate profile
- Changes requested on an event

Use the notification interface to open the related item when a notification is available.

---

## 13. AI Generation Review

Admins and Reviewers can access the AI generation review area.

AI-generated content is tracked separately from the original record and can have statuses including:

- Pending review
- Approved
- Rejected
- Edited

This allows AI assistance to remain part of an editorial workflow rather than automatically replacing human review.

---

## 14. Public Yearbook

The public side of the application does not require authentication.

Main public areas include:

- Yearbook archive
- Academic-year yearbook pages
- Graduate directory
- Graduate profiles
- Graduate resumes where applicable
- Events
- Event details
- Graduations
- Graduation details
- Timeline
- Search
- Graduate PDF profiles
- Academic-year PDF yearbooks

### Graduate profile URLs

Graduate public profiles use the graduate's **student reference** in the URL rather than the database primary key.

Example:

```text
/graduates/STU-2026-015
```

The same student reference is used for the public resume and graduate PDF routes.

---

## 15. Recommended Content Workflow

For content that requires editorial review, use the following workflow:

```text
Create / Edit
      ↓
Submit for Review
      ↓
Reviewer Notification
      ↓
Review
   ↙       ↘
Approve   Request Changes
   ↓          ↓
Publish    Editor Updates
              ↓
        Submit Again
```

### Editor

Create or update the content, then submit it for review when ready.

### Reviewer

Check the content, approve it when ready, or request specific changes using the review note.

### Admin

Admins have the broadest access and can perform administrative, content, review, and publishing tasks.

---

## 16. Common Tasks at a Glance

| Task | Admin | Editor | Reviewer |
|---|:---:|:---:|:---:|
| View dashboard | ✓ | ✓ | ✓ |
| View graduates | ✓ | ✓ | ✓ |
| Create/edit graduates | ✓ | ✓ | — |
| Submit graduates for review | ✓ | ✓ | — |
| Review graduates | ✓ | — | ✓ |
| Approve/request changes | ✓ | — | ✓ |
| Publish graduates | ✓ | — | ✓ |
| View events | ✓ | ✓ | ✓ |
| Create/edit events | ✓ | ✓ | — |
| Submit events for review | ✓ | ✓ | — |
| Review events | ✓ | — | ✓ |
| Publish events | ✓ | — | ✓ |
| Manage media | ✓ | ✓ | — |
| View media | ✓ | ✓ | ✓ |
| Manage academic years | ✓ | ✓ | — |
| Manage schools/campuses/majors/categories | ✓ | ✓ | — |
| Manage graduations | ✓ | ✓ | — |
| Generate graduate biography | ✓ | ✓ | — |
| Generate event summary | ✓ | ✓ | — |
| Review AI generations | ✓ | — | ✓ |
| Manage users | ✓ | — | — |
| Access settings | ✓ | — | — |
| View audit logs | ✓ | — | — |

---

## 17. Troubleshooting

### I cannot access an administration page

Check that:

1. You are signed in.
2. Your account is verified where required.
3. Your assigned role has permission for that section.

### I cannot see a draft record as a Reviewer

Draft graduates and draft events are hidden from the Reviewer content lists. The content must be submitted for review before it enters the review workflow.

### A graduate is not visible publicly

Check all relevant conditions, including:

- The graduate has been published.
- The graduate has granted consent.
- The profile has the required public information.
- The public URL uses the correct **student reference**.

### An AI result is not immediately public

AI-generated content is stored with a review status such as **pending review**. Review the generated content before using it as final editorial content.

### Changes requested by a reviewer are still visible

Open the graduate or event record, read the open review feedback, make the requested corrections, and save the record. Updating the record resolves the open review feedback associated with it.

---

## 18. Best Practices

- Complete and verify content before submitting it for review.
- Use clear review notes that explain exactly what needs to change.
- Check names, dates, schools, campuses, majors, and academic years carefully.
- Provide alternative text for public images where appropriate.
- Review AI-generated text, captions, and tags before treating them as final content.
- Do not publish content until the relevant consent and editorial requirements are satisfied.
- Keep media captions, tags, and credits accurate.
- Use the audit log when investigating administrative changes.
- Keep older academic years organized and archived appropriately.

---

## 19. Related Documentation

- [Technical Guide](technical-guide.md)
- [Project README](../README.md)
