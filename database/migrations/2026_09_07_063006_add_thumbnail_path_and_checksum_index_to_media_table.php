<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * thumbnail_path holds the disk-relative path to a generated preview
     * image (see App\Services\ImageProcessor). It's nullable because not
     * every media item is an image, and thumbnail generation is
     * best-effort (skipped for small images or if GD is unavailable).
     *
     * The index on checksum supports duplicate-upload detection
     * (Media::findByChecksum()) without a full table scan.
     */
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->string('thumbnail_path')->nullable()->after('path');
            $table->index('checksum');
        });
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropIndex(['checksum']);
            $table->dropColumn('thumbnail_path');
        });
    }
};
