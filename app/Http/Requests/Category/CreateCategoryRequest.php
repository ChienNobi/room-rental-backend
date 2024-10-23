<?php

namespace App\Http\Requests\Category;

use App\Constants\Common;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCategoryRequest extends FormRequest
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
            'type' => ['required', Rule::in(array_values(Common::CATEGORY_TYPE))],
            'icon' => ['nullable'],
            'report_exclude' => ['nullable', 'boolean'],
        ];
    }
}
