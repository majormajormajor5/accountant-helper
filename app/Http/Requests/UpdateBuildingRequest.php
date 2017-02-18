<?php

namespace App\Http\Requests;

use App\Building;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateBuildingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->segment(count(request()->segments()));
        if (Building::where('id', $id)->where('user_id', Auth::user()->id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'organization_id' => ['required',
                Rule::exists('organizations', 'id')->where(function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })]
        ];
    }
}
