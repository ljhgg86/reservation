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
        return [
            //'typeName'=>'required|unique:ordertype,typeName|max:50',
            'typeName' => ['required',
                            'max:50',
                            Rule::unique('ordertype')->where(function($query){
                                $query->where('delFlag',0);
                            })
                        ]
        ];
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
        ];
    }
}
