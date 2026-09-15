<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->text('certifications_training')->nullable()->after('internships');
            $table->text('professional_interests')->nullable()->after('future_plans');
            $table->json('approved_links')->nullable()->after('professional_interests');
        });
    }

    public function down(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->dropColumn([
                'certifications_training',
                'professional_interests',
                'approved_links',
            ]);
        });
    }
};
