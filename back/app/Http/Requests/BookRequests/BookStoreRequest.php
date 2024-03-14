<?php

namespace App\Http\Requests\BookRequests;

use Illuminate\Foundation\Http\FormRequest;

class BookStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100|min:3',
            'description' => 'nullable|string|max:300',
            'ISBN' => 'nullable',
            'published_at' => 'nullable',
            'genre' => 'nullable|string|max:20',
            'language' => 'nullable|string|max:20',
            'pages' => 'nullable',
            'publisher' => 'nullable|string|max:100',
            'file' => 'nullable|file|mimes:pdf,docx,epub,mobi,djvu|max:512000'
        ];
    }
}
