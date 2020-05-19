<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        switch($this->method())
        {
            // CREATE
            case 'POST':
            {
                return [
                    'name' => [
                        'required',
                        'max:50',
                        'min:1',
                        Rule::unique('users')->where(function($query){
                            $query->where('deleted_at',NULL);
                        })
                    ],
                    'password' => [
                        'required',
                        'max:20',
                        'min:6'
                    ]
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                $id = $this->segment(4);
                return [
                    'name' => [
                        'max:50',
                        'min:1',
                        Rule::unique('users')->where(function($query) use($id) {
                            $query->where('deleted_at',NULL)
                                ->where('id', '!=', $id);
                        })
                    ]
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
     /**
     * 获取被定义验证规则的错误消息
     *
     * @return array
     * @translator laravelacademy.org
     */
    public function messages(){
        return [
            'name.required' => '名称不能为空',
            'name.unique'  => '名称已被占用',
            'name.max' => '名称长度太长',
            'name.min' => '名称长度太短',
            'password.required' => '密码不能为空',
            'password.max' => '密码长度太长',
            'password.min' => '密码长度太短',
        ];
    }
}
