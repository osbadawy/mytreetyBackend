<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAddressRequest extends FormRequest
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

        return[
            'country_id' => 'required',
            'city_id' => 'required',
            'postal_code' => 'required',
            'address' => 'required',
            'first_name'=>'required',
            'last_name' =>'required',
        ];
    }
}
