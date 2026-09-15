<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->foreignId('academic_year_id')->nullable()->after('graduation_id')->constrained('academic_years');
        });

        // Existing graduates inherit their academic year from their assigned ceremony.
        // Use the query builder instead of MySQL-specific UPDATE ... JOIN syntax so
        // this migration works with both MariaDB/MySQL and SQLite test databases.
        DB::table('graduates')
            ->whereNull('academic_year_id')
            ->get()
            ->each(function ($graduate) {
                $academicYearId = DB::table('graduations')
                    ->where('id', $graduate->graduation_id)
                    ->value('academic_year_id');

                if ($academicYearId !== null) {
                    DB::table('graduates')
                        ->where('id', $graduate->id)
                        ->update([
                            'academic_year_id' => $academicYearId,
                        ]);
                }
            });

        // Every existing graduate had a required graduation_id, so the backfill above
        // should populate every row. Make the new yearbook assignment authoritative.
        Schema::table('graduates', function (Blueprint $table) {
            $table->foreignId('academic_year_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropColumn('academic_year_id');
        });
    }
};
