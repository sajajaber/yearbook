<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->constrained('schools')->after('student_reference');
            $table->text('profile_text')->nullable();
            $table->json('achievements')->nullable();
            $table->json('activities')->nullable();
            $table->json('projects')->nullable();
            $table->json('internships')->nullable();
            $table->text('future_plans')->nullable();
            $table->string('quote')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn([
                'school_id',
                'profile_text',
                'achievements',
                'activities',
                'projects',
                'internships',
                'future_plans',
                'quote',
            ]);
        });
    }
};
