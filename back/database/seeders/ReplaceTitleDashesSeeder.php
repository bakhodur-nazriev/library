<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Seeder;

class ReplaceTitleDashesSeeder extends Seeder
{
    public function run(): void
    {
        Book::query()
            ->chunk(50, function ($bookList) {
                foreach ($bookList as $book) {
                    $book->title = str_replace('_', ' ', $book->title);
                    $book->save();
                }
            });
    }

}
