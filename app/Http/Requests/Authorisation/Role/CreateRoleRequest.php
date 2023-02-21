<?php

namespace App\Http\Requests\Authorisation\Role;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'label' => [
                'required',
            ],
            'value' => [
                'required',
                'unique:roles',
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
