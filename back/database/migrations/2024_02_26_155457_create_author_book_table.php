<?php

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('author_book', function (Blueprint $table) {
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('book_id');

            $table->foreign('author_id')
                ->references('id')
                ->on('authors')
                ->onDelete('cascade');

            $table->foreign('book_id')
                ->references('id')
                ->on('books')
                ->onDelete('cascade');
        });

        //test
        $book = Book::query()
            ->create([
                'title' => 'Sample Book Title',
                'link' => 'qweqweqwe',
                'description' => 'This is a sample book description.',
                'ISBN' => '1234567890',
                'published_at' => now(),
                'cover_image' => 'qweqweqwe',
                'genre' => 'Fiction',
                'language' => 'English',
                'pages' => 250,
                'publisher' => 'Sample Publisher',
                'search_key' => 'sample, book, fiction',
            ]);

        //test
        $author = Author::query()
            ->create([
                "date_of_birth" => "2000-11-11",
                "initials" => "test test",
                "book_ids" => [],
                "nationality" => "test",
                "biography" => "test"
            ]);

        $author->books()->attach([$book->id]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_book');
    }
};
