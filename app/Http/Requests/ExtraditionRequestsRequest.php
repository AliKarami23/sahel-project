<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExtraditionRequestsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reserve_id' => 'required|numeric',
            'order_id' => 'required|numeric',
            'capacity_man' => 'required|numeric',
            'capacity_woman' => 'required|numeric',
            'card_number' => 'required|string|digits:9',
            'name_card' => 'required|string|max:255',
        ];
    }
}
