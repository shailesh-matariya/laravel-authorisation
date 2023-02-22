<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required'
            ],
            'email' => [
                'required',
                'email',
                'unique:users'
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8'
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
