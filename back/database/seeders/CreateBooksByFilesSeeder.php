<?php

namespace Database\Seeders;

use App\Models\Book;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CreateBooksByFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws Exception
     */
    public function run(): void
    {
        $files = Storage::files('pdfs');

        $bar = $this->command->getOutput()->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $file) {
            $title = pathinfo($file, PATHINFO_FILENAME);
            $link = Storage::url($file);

            $book = new Book();
            $book->title = $title;
            $book->link = $link;
            $book->save();

            $bar->advance();
        }

        $bar->finish();
        $this->command->info("\nBooks seeded successfully.");
    }

}
