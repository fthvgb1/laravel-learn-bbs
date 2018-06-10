<?php

namespace App\Http\Requests\Api;

class SocialAuthorizationRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'code' => 'required_without:access_token|string',
            'access_token' => 'required_without:code|string',
            'openid' => 'required_with:access_token|string'
        ];

        if ($this->get('social_type') == 'weixin' && !$this->get('code')) {
            $rules['openid'] = 'required|string';
        }

        return $rules;
    }
}
