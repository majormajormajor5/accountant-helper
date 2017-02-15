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
//        dd(Organization::where('id', $id)->exists());
        $isAuthorized = !!Organization::where('id', $id)->exists();
        if ($isAuthorized) {
            return true;
        }

        return false;
//        return $isAuthorized;
//        $org = Organization::where('id', 89);
//        return !!$org;
//        switch($this->method())
//        {
//            case 'POST':
//            case 'PUT':
//            case 'GET':
//            case 'PATCH':
//            case 'DELETE':
//                return Organization::where('id', 89)->where('user_id', Auth::user()->id)->exists();
//                break;
//            default:
//                break;
//        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:organizations|max:255'
        ];
    }
}
