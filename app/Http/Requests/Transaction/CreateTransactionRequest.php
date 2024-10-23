<?php

namespace App\Http\Requests\Transaction;

use App\Constants\Common;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'group_id' => ['nullable'],
            'amount' => 'required|numeric',
            'category_id' => ['required', 'exists:categories,id'],
            'wallet_id' => ['required', 'exists:wallets,id'],
            'image' => ['nullable'],
            'report_exclude' => ['nullable', 'boolean'],
            'action_time' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
