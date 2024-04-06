<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Seeder;

class SeparateBookTitleFromAuthorSeeder extends Seeder
{
   // private string $pattern = '/([а-яА-Я]+(?:ова|ов|ева|ев|вич|ьев|ьева|ин|ен|ко|арь|скии)\s*[А-Я]\.\s*[А-Я]\.)\s*(.*)/u';

    private string $pattern = '/([а-яА-Я]+(?:ова|ов|ева|ев|вич|ьев|ьева|ин|ен|ко|арь|скии)\s*[А-Я]\.)\s*(.*)/u';

    //private string $pattern = '/([а-яА-Я]+(?:ова|ов|ева|ев|вич|ьев|ьева|ин|ен|арь|скии))\s*(.*)/u';


    public function run(): void
    {
        Book::query()
            ->chunk(50, function ($bookList) {
                foreach ($bookList as $book) {
                    $title = $book->title;
                    if (preg_match($this->pattern, $title, $matches)) {
                        //
                        $book->title = $matches[2];
                        $book->save();

                        //
                        $author = new Author();
                        $author->initials = $matches[1];
                        $author->save();

                        //
                        $this->addAuthors($author->id, $book);
                    }
                }
            });
    }

    private function addAuthors(int $authorId, $book): void
    {
            $book->authors()->attach([$authorId]);
            $this->reindexAuthor($book);
            $book->save();
    }

    private function reindexAuthor($book): void
    {
        $searchKey = $book->title;
        foreach ($book->authors as $author) {
            $searchKey .= $author->initials;
        }

        $book->search_key = $searchKey;
    }

}
