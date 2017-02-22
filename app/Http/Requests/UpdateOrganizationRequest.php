<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Organization;
use Illuminate\Support\Facades\Auth;

class UpdateOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->segment(count(request()->segments()));
        if (Organization::where('id', $id)->where('user_id', Auth::user()->id)->exists()) {
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
        $id = $this->segment(count(request()->segments()));

        return [
            'name' => 'required|unique:organizations,name,' . $id . '|max:255'
        ];
    }
}
