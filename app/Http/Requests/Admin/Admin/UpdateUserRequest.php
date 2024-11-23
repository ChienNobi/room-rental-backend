<?php

namespace App\Http\Requests\Admin\Admin;

use App\Constants\Role;
use App\Rules\NoSpaces;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['email', 'unique:users,email,' . request()->route('id') . ',id', 'max:64', new NoSpaces],
            'phone' => ['numeric', 'digits:10', 'unique:users,phone,' . request()->route('id') . ',id'],
        ];
    }
}
