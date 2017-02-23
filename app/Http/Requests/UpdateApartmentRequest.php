<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'number_of_residents' => 'integer',
            'square' => 'regex:/^[0-9]{1,6}\.[0-9]{0,4}$/',
            'user_id' => 'not_present'
        ];
    }
}
