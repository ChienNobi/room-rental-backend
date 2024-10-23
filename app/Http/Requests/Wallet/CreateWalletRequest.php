<?php

namespace App\Http\Requests\Wallet;

use App\Constants\Common;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateWalletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'group_id' => ['nullable'],
            'name' => 'required',
            'description' => ['nullable'],
            'total' => ['required', 'numeric'],
            'icon' => ['nullable'],
            'report_exclude' => ['nullable', 'boolean'],
        ];
    }
}
