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
        Schema::create('authors_search_keys', function (Blueprint $table) {

            $table->unsignedBigInteger('author_id');
            $table->string('search_key');
            //
            $table->foreign('author_id')
                ->references('id')
                ->on('authors')
                ->onDelete('cascade');

        });

        DB::statement('CREATE INDEX asearch_key_trgm_idx ON authors_search_keys USING GIN (search_key gin_trgm_ops)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors_search_keys');
    }
};
