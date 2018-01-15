<?php

namespace App\Http\Requests;

class ReplyRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            // CREATE
            case 'POST':
                {
                    return [
                        'content' => 'required|max:255',
                        'topic_id' => 'required|integer'
                    ];
                }
            // UPDATE
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        // UPDATE ROLES
                    ];
                }
            case 'GET':
            case 'DELETE':
            default:
                {
                    return [];
                };
        }
    }

    public function messages()
    {
        return [
            // Validation messages
        ];
    }


    public function attributes()
    {
        return [
            'content' => '回复内容',
            'topic_id' => '回复的主题'
        ];
    }
}
