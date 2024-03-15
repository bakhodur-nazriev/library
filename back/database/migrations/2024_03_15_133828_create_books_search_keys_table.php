<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books_search_keys', function (Blueprint $table) {
            $table->unsignedBigInteger('book_id');
            $table->string('search_key');
            //
            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('cascade');
        });

        DB::statement('CREATE INDEX bsearch_key_trgm_idx ON books_search_keys USING GIN (search_key gin_trgm_ops)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books_search_keys');
    }
};
