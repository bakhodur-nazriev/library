<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'description', 'ISBN', 'published_at', 'cover_image_link', 'genre', 'language', 'pages', 'publisher', 'link'];

}
