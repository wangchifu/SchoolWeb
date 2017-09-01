<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinkRequest extends FormRequest
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
            'title' => 'required',
            'block_id' => 'required|int',
        ];
    }
    public function attributes()
    {
        return [
            'title' => '標題',
            'block_id' => '區塊'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => ':attribute 不可空白',
            'block_id.required' => ':attribute 沒有選擇'
        ];
    }
}
