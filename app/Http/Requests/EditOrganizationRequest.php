<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        switch($this->method())
        {
            case 'POST':
            {
                return true;
            }
            case 'PUT':
            case 'GET':
            case 'PATCH':
            case 'DELETE':
            {
                return Post::where('id', $this->blog)->where('user_id', $this->user()->id)->exists();
            }
            default:break;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
