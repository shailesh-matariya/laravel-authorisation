<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AssignRolesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id'
            ],
            'roles' => [
                'required',
                'array'
            ],
            'roles.*' => [
                'exists:roles,id'
            ],
        ];
    }

    public function messages()
    {
        return [
            'roles.*.exists' => 'The selected roles is invalid.'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
