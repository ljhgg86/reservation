<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrdertimeruleRequest extends FormRequest
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
            'object_id' => 'required',
            'weekDate' => 'required',
            'endTime' => 'gte:beginTime',
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
            'object_id.required' => '必须指定实物',
            'weekDate.required' => '星期不能为空',
            'endTime.gte' => '结束时间必须不小于开始时间',
        ];
    }
}
