<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'title' => 'required|string',
            'price' => 'required|numeric',
            'video_id' => 'required|numeric',
            'image_id' => 'required|array',
            'image_id.*' => 'numeric',
            'image_main_id' => 'required|numeric',
            'discount_type' => 'required|string|in:Percent,Amount',
            'discount_amount' => 'required|numeric',
            'age_limit' => 'required|string',
            'age_limit_value' => 'required|numeric',
            'total_start' => 'required|numeric',
            'total_end' => 'required|numeric',
            'break_time' => 'required|numeric',
            'rules' => 'required|string',
            'description' => 'required|string',
            'extradition' => 'required|string|in:yes,no',
            'extradition_percent' => 'required_if:extradition,yes|numeric',
            'extradition_time' => 'required_if:extradition,yes|numeric',
            'sans' => 'required|array',
            'sans.*.product_id' => 'required|numeric',
            'sans.*.start' => 'required|numeric',
            'sans.*.end' => 'required|numeric',
            'sans.*.capacity_man' => 'required|numeric',
            'sans.*.capacity_woman' => 'required|numeric',
            'sans.*.date' => 'required|date_format:Y-m-d',
        ];
    }
}
