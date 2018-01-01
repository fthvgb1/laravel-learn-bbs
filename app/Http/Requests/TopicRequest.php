<?php

namespace App\Http\Requests;

class TopicRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            // CREATE
            case 'POST':
                {
                    return [
                        'title' => 'required|max:50',
                        'category_id' => 'required|integer',
                        'body' => 'required|min:3'
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

    public function attributes()
    {
        return [
            'title' => '标题',
            'category_id' => '类别',
            'body' => '内容'
        ];
    }

    public function messages()
    {
        return [
            // Validation messages
        ];
    }
}
