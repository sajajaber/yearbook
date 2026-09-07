<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_images', function (Blueprint $table) {
            $table->foreignId('media_id')->after('id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('display_order')->default(0)->after('media_id');
        });
    }

    public function down(): void
    {
        Schema::table('hero_images', function (Blueprint $table) {
            $table->dropForeign(['media_id']);
            $table->dropColumn(['media_id', 'display_order']);
        });
    }
};
