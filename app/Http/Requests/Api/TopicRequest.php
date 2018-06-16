<?php

namespace App\Http\Requests\Api;


/**
 * Class TopicRequest
 * @property string title
 * @property string body
 * @property int category_id
 * @package App\Http\Requests\Api
 */
class TopicRequest extends FormRequest
{

    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'title' => 'required|string',
                    'body' => 'required|string',
                    'category_id' => 'required|exists:categories,id',
                ];
            case 'PATCH':
                return [
                    'title' => 'string',
                    'body' => 'string',
                    'category' => 'exists:categories,id'
                ];
            default:
                return [];
        }


    }

    public function attributes()
    {
        return [
            'title' => '标题',
            'body' => '话题内容',
            'category_id' => '分类',
        ];
    }
}
