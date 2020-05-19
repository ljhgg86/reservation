<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuthorityRequest extends FormRequest
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
                    'authorityName' => ['required',
                            'max:50',
                            'min:1',
                            Rule::unique('authority')->where(function($query){
                                $query->where('deleted_at',NULL);
                            })
                        ]
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                $id = $this->segment(4);
                return [
                    'authorityName' => ['max:50',
                            'min:1',
                            Rule::unique('authority')->where(function($query) use($id) {
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
            'authorityName.required' => '权限名称不能为空',
            'authorityName.unique'  => '权限名称已被占用',
            'authorityName.max' => '权限名称长度太长',
            'authorityName.min' => '权限名称长度太短',
        ];
    }
}
