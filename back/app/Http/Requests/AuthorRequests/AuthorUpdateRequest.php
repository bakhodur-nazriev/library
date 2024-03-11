<?php

namespace App\Http\Requests\AuthorRequests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $authorId = $this->route('id');
        $this->merge(['author_id' => $authorId]);

        return [
            'initials' => 'nullable|string|min:5|max:200',
            'date_of_birth' => 'nullable|string',
            'book_ids' => 'nullable|array',
            'author_id' => ['nullable', 'exists:authors,id'],
        ];
    }
}
