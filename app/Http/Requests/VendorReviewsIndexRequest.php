<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorReviewsIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'order' => 'in:desc,asc',
            'sort' => 'in:product_name,rating,created_at',
        ];
    }
}
