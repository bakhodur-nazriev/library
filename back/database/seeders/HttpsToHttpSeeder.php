<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class HttpsToHttpSeeder extends Seeder
{

    public function run(): void
    {
        Book::query()
            ->chunkById(100, function ($books) {
                foreach ($books as $b) {
                    $b->cover_image = str_replace('https', 'http', $b->cover_image);
                    $b->save();
                }
            });

    }
}
