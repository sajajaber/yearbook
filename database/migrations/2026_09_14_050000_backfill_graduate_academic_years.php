<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // The academic year is already backfilled by the preceding
        // 2026_09_13_100000_add_academic_year_to_graduates_table migration.
        // This migration is intentionally a no-op to avoid a redundant
        // cross-table UPDATE that is not portable to SQLite.
    }

    public function down(): void
    {
        // Academic-year membership is intentionally retained on rollback.
    }
};
