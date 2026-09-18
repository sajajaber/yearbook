<?php

use App\Models\Graduate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->uuid('public_slug')->nullable()->unique()->after('id');
        });

        Graduate::query()
            ->whereNull('public_slug')
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, function ($graduates) {
                foreach ($graduates as $graduate) {
                    Graduate::whereKey($graduate->id)->update([
                        'public_slug' => (string) Str::uuid(),
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('graduates', function (Blueprint $table) {
            $table->dropUnique('graduates_public_slug_unique');
            $table->dropColumn('public_slug');
        });
    }
};
