<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->enum('publish_status', ['draft', 'reviewed', 'approved', 'published', 'archived', 'rejected'])
                ->default('draft')
                ->change();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->enum('status', ['draft', 'reviewed', 'approved', 'published', 'archived', 'rejected'])
                ->default('draft')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->enum('publish_status', ['draft', 'reviewed', 'approved', 'published', 'archived'])
                ->default('draft')
                ->change();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->enum('status', ['draft', 'reviewed', 'approved', 'published', 'archived'])
                ->default('draft')
                ->change();
        });
    }
};
