<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CharitySignUp2Request extends FormRequest
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
            'legal_name' => 'required',
            'charity_name' => 'required',
            'description' => 'required',
            'website' => 'required',
        ];
    }
}
