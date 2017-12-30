<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserUpdate extends FormRequest
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
            'name' => ['required', 'min:3', 'max:20',
                'regex:/^[0-9A-Za-z\-\_]+$/',
                'unique:users,name,' . Auth::id()
            ],
            'email' => ['required', 'unique:users,email,' . Auth::id()],
            'introduction' => 'nullable|max:255',
            'avatar' => 'image|nullable',
        ];
    }


    public function attributes()
    {
        return [
            'introduction' => '简介',
        ];
    }
}
