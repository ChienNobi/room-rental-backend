<?php

namespace App\Http\Requests;

use App\Constants\Common;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'images' => ['required', 'array'],
            'city' => ['required', 'exists:location_cities,id'],
            'district' => ['required', 'exists:location_districts,id'],
            'ward' => ['required', 'exists:location_wards,id'],
            'detail_address' => ['required', 'string'],
            'lat' => ['required', 'string'],
            'lon' => ['required', 'string'],
            'room_type' => ['required', Rule::in(Common::ROOM_TYPES)],
            'acreage' => ['nullable', 'numeric'],
            'rent_fee' => ['required', 'numeric'],
            'electricity_fee' => ['nullable', 'numeric'],
            'water_fee' => ['nullable', 'numeric'],
            'internet_fee' => ['nullable', 'numeric'],
            'extra_fee' => ['nullable', 'numeric'],
            'furniture' => ['nullable', Rule::in(Common::FURNITURE_TYPES)],
            'furniture_detail' => ['nullable', 'string'],
            'room_number' => ['required', 'integer'],
            'contact_name' => ['required', 'string'],
            'contact_email' => ['required', 'email'],
            'contact_phone' => ['required', 'string'],
            'status' => ['required', Rule::in(Common::POST_STATUSES)],
        ];
    }
}
