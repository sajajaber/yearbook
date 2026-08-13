<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /* this function runs when you do php artisan migrate */
    public function up(): void
    {
        // actual instruction
        Schema::create('roles', function (Blueprint $table) {
            // creates an auto-incrementing primary key column called id
            $table->id();
            $table->string('role_name')->unique();
            $table->json('permissions')->nullable();
            /* JSON object/array because a role might have several permissions */
            
            $table->timestamps();
            /*  creates two columns, created_at and updated_at, and Laravel fills them in  automatically whenever a row is created or changed */ 
            /*  $role = Role::find(1);       fetch the role with id = 1
                echo $role->created_at;      e.g. "2026-08-12 14:30:05"
                echo $role->updated_at;  */
        });
    }

    /* "undo" function
    if you ever run php artisan migrate:rollback */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
