# LIU Digital Yearbook — Backup & Restore

## Database backup

For MariaDB/MySQL, create a logical backup of the `yearbook_db` database:

```bash
mysqldump -u <username> -p --single-transaction --routines --triggers yearbook_db > yearbook_backup_YYYY-MM-DD.sql
```

Store the backup outside the application repository and protect it as an administrative artifact.

## Database restore test

Use a separate test database. Restore the backup with:

```bash
mysql -u <username> -p yearbook_restore_test < yearbook_backup_YYYY-MM-DD.sql
```

Then verify:

- the application can connect to the restored database;
- academic years, graduates, events, graduations, media records, users, and audit logs are present;
- representative public pages load;
- graduate PDF generation works;
- no required foreign-key relationships are broken.

A backup is not considered validated until a restore has been completed successfully.

## Uploaded files

The database backup does not include the contents of `storage/app/public` or any private storage directory. Back up uploaded media separately, preserving the directory structure.

Before handover, test both the database restore and media restore together on a clean environment.

## Production practice

Use a scheduled backup job appropriate to the hosting environment, retain multiple historical copies, and keep at least one copy outside the application server.
