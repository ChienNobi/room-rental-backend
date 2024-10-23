<?php

namespace App\Http\Requests\Transaction;

use App\Constants\Common;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'wallet_id' => ['nullable', 'exists:wallets,id'],
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d'],
        ];
    }
}
