<?php

namespace App\Http\Requests\AuthorRequests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorStoreRequest extends FormRequest
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
            'initials' => 'required|string|min:5|max:200',
            'date_of_birth' => 'nullable|date',
            'book_ids' => 'nullable|array',
            'nationality' => 'nullable|string',
            'biography' => 'nullable|string',
            'photo' => 'file|mimes:png|max:100'
        ];
    }
}
