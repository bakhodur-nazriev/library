<?php

namespace App\Jobs;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IndexBookSearchKeysJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(private readonly Book $book){}

    public function handle(): void
    {
        $searchKey = $this->book->title;

        foreach ($this->book->authors as $author) {
            $searchKey .= $author->initials;
        }

        $this->book->search_key = $searchKey;
        $this->book->save();
    }
}
