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
            'ISBN' => 'nullable|unique:books,ISBN|numeric|max:20000000000',
            'published_at' => 'nullable|date',
            'genre' => 'nullable|string|max:20',
            'language' => 'nullable|string|max:20',
            'pages' => 'nullable|integer|max:1000',
            'publisher' => 'nullable|string|max:100',
            'pdf' => 'file|mimes:pdf|max:51200'
        ];
    }
}
