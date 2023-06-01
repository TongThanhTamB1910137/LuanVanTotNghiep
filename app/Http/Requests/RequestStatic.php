<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestStatic extends FormRequest
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
        $rules =  [
            'ten' => 'required',
            'diachi' => 'required',
            'email' => 'required',
            'dienthoai' => 'required',
        ];
        return $rules;
    }
    public function messages()
    {
        return [
            'ten.required' => "Tên không để trống.",
            'diachi.required' => "Địa chỉ không để trống.",
            'email.required' => "Email không để trống.",
            'dienthoai.required' => "Số điện thoại không để trống.",
        ];
    }
}