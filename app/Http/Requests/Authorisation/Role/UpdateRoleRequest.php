<?php

namespace App\Http\Requests\Authorisation\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'label' => [
                'required'
            ],
            'value' => [
                'required',
                Rule::unique('roles')->whereNot('id', $this->route('id')),
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
