<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGiftCardRequest extends FormRequest
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
            'desgin' => 'required',
            'amount' => 'required',
            'email' => 'required',
            'delivary_date' => 'required',
            'signature' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ];
    }
}
