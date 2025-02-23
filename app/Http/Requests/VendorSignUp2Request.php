<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorSignUp2Request extends FormRequest
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
            'orders_email' => 'required|email',
            'registration_number' => 'required',
            'person_name' => 'required',
            'person_email' => 'required|email',
            'person_phone' => 'required',
            'country' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            'address' => 'required',
            'bank_name' => 'required',
            'bank_acc_name' => 'required',
            'bank_acc_no' => 'required',
            'bank_iban' => 'required',
        ];
    }
}
