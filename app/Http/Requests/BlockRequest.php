<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlockRequest extends FormRequest
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
            'name' => 'required',
            'style' => 'required',
        ];
    }
    public function attributes()
    {
        return [
            'name' => '名稱',
            'style' => '位置'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute 不可空白',
            'style.required' => ':attribute 沒有選擇'
        ];
    }
}
