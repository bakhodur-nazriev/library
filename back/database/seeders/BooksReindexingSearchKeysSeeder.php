<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Services\SearchKeysService;
use Illuminate\Database\Seeder;

class BooksReindexingSearchKeysSeeder extends Seeder
{

    public function run(): void
    {
        Book::query()
            ->chunkById(50, function ($books) {
                foreach ($books as $book) {
                    (new SearchKeysService($book))->store();
                }
            });

    }
}
