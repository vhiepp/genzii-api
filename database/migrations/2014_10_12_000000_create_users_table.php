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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name');
            $table->string('sur_name')->nullable();
            $table->string('given_name')->nullable();
            $table->string('slug');
            $table->string('email')->unique();
            $table->string('date_of_birth')->length(50)->nullable();
            $table->enum('gender', ["male", "female", "other"])->default("other");
            $table->string('address')->nullable();
            $table->enum('role', ["user", "admin"])->default("user");
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
