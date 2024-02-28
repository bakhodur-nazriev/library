<?php

namespace App\Http\Requests\BookRequests;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100|min:3',
            'description' => 'nullable|string|max:300',
            'ISBN' => 'unique:books,ISBN|numeric|max:20',
            'published_at' => 'nullable|date',
            'genre' => 'nullable|string|max:20',
            'language' => 'nullable|string|max:20',
            'pages' => 'nullable|integer|max:1000',
            'publisher' => 'nullable|string|max:100',
            'pdf' => 'required|file|mimes:pdf|max:2048'
        ];
    }
}
