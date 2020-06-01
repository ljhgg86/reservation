<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrdertypeRequest extends FormRequest
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
                    'typeName' => ['required',
                            'max:50',
                            'min:1',
                            Rule::unique('ordertype')->where(function($query){
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
                    'typeName' => ['max:50',
                            'min:1',
                            Rule::unique('ordertype')->where(function($query) use($id) {
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
        // return [
        //     'typeName' => ['required',
        //                     'max:50',
        //                     Rule::unique('ordertype')->where(function($query){
        //                         $query->where('delFlag',0);
        //                     })
        //                 ]
        // ];
    }

    /**
     * 获取被定义验证规则的错误消息
     *
     * @return array
     * @translator laravelacademy.org
     */
    public function messages(){
        return [
            'typeName.required' => '类型名称不能为空',
            'typeName.unique'  => '类型名称已被占用',
            'typeName.max' => '类型名称长度太长',
            'typeName.min' => '类型名称长度太短',
        ];
    }
}
