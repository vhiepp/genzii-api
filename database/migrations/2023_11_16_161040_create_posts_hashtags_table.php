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
        Schema::create('posts_hashtags', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('post_id');
            $table->string('hashtag_id');
            $table->string('created_at')->length(50);
            $table->string('updated_at')->length(50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts_hashtags');
    }
};
