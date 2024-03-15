<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'ISBN',
        'published_at',
        'cover_image_link',
        'genre',
        'language',
        'pages',
        'publisher',
        'link'
    ];

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    public function searchKeys(): HasMany
    {
        return $this->hasMany(BookSearchKey::class, 'book_id', 'id');
    }
}
