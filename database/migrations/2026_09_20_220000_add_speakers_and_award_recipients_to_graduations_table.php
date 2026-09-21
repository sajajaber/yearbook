<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graduations', function (Blueprint $table) {
            $table->json('speakers')->nullable()->after('description');
            $table->json('award_recipients')->nullable()->after('speakers');
        });
    }

    public function down(): void
    {
        Schema::table('graduations', function (Blueprint $table) {
            $table->dropColumn(['speakers', 'award_recipients']);
        });
    }
};
