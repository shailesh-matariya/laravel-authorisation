<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required'
            ],
            'email' => [
                'required',
                Rule::unique('users')->whereNot('id', $this->route('user')->id)
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
