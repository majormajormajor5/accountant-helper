<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreApartmentRequest extends FormRequest
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
//            'building_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:300',
            'number' => 'required_without_all:fromNumber',
            'fromNumber' => 'required_without_all:number',
            'buildingId' => ['required',
                Rule::exists('buildings', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })]
        ];
    }
}
