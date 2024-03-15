<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthorSearchKey extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'authors_search_keys';

    public $timestamps = false;

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

}
