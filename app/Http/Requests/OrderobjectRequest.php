<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderobjectRequest extends FormRequest
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
            'type_id' => 'required',
            'objectName' => ['required',
                            'max:50',
                            Rule::unique('orderobject')->where(function($query){
                                $query->where('delFlag',0)
                                        ->where('type_id',request('type_id'));
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
            'type_id.required' => '必须指定类型',
            'objectName.required' => '名称不能为空',
            'objectName.unique'  => '名称已被占用',
            'objectName.max' => '名称长度太长',
        ];
    }
}
