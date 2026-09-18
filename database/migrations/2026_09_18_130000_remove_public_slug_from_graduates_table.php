<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->dropUnique('graduates_public_slug_unique');
            $table->dropColumn('public_slug');
        });
    }

    public function down(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->uuid('public_slug')->nullable()->unique()->after('id');
        });
    }
};
