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
        DB::statement('
            UPDATE graduates g
            INNER JOIN graduations gr ON gr.id = g.graduation_id
            SET g.academic_year_id = gr.academic_year_id
            WHERE g.academic_year_id IS NULL
        ');

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
