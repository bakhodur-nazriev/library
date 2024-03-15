<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'initials',
        'nationality',
        'biography',
        'date_of_birth',
        'photo_link'
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }

    public function searchKeys(): HasMany
    {
        return $this->hasMany(BookSearchKey::class, 'author_id', 'id');
    }

}
