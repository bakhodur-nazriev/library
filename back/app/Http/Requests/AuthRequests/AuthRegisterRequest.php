<?php

namespace App\Http\Requests\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:100',
            'password' => 'required|min:8',
            'name' => 'required|string|min:3|max:100'
        ];
    }
}
