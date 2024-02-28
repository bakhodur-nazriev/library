<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('link')->nullable();
            $table->text('description')->nullable();
            $table->string('ISBN')->unique();
            $table->date('published_at')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('genre')->nullable();
            $table->string('language')->nullable();
            $table->integer('pages')->nullable();
            $table->string('publisher')->nullable();
            $table->string('search_key')->nullable();
            $table->timestamps();
        });

        DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');
        DB::statement('CREATE INDEX search_key_trgm_idx ON books USING GIN (search_key gin_trgm_ops)');
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
