<?php

namespace App\Http\Requests\Admin\Admin;

use App\Constants\Role;
use App\Rules\NoSpaces;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email', 'max:64', new NoSpaces],
            'phone' => ['required', 'numeric', 'digits:10', 'unique:users,phone'],
            'address' => ['required'],
            'password' => 'required|min:8',
        ];
    }
}
