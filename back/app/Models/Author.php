<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'initials',
        'date_of_birth',
        'photo_link'
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class);
    }

}
