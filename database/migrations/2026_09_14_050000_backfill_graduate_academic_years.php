<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('graduates', 'academic_year_id')) {
            return;
        }

        DB::table('graduates')
            ->join('graduations', 'graduates.graduation_id', '=', 'graduations.id')
            ->whereNull('graduates.academic_year_id')
            ->update([
                'graduates.academic_year_id' => DB::raw('graduations.academic_year_id'),
            ]);
    }

    public function down(): void
    {
        // Academic-year membership is intentionally retained on rollback.
    }
};
