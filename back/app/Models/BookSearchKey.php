<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookSearchKey extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'books_search_keys';

    public $timestamps = false;

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

}
