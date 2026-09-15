<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->foreignId('graduation_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Existing graduates without a ceremony cannot be converted back
        // to a non-nullable graduation_id safely, so fail rather than
        // silently assigning an unrelated ceremony.
        if (Schema::hasColumn('graduates', 'graduation_id')) {
            $missingGraduation = \DB::table('graduates')
                ->whereNull('graduation_id')
                ->exists();

            if ($missingGraduation) {
                throw new \RuntimeException('Cannot make graduation_id non-nullable while graduates without a ceremony exist.');
            }

            Schema::table('graduates', function (Blueprint $table) {
                $table->foreignId('graduation_id')->nullable(false)->change();
            });
        }
    }
};
