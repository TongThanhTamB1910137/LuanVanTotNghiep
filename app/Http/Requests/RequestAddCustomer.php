<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestAddCustomer extends FormRequest
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
            'ten' => 'required|max:100',
            'email' => 'required|min:3|max:100|unique:customers,email,' . $this->id,
            'sodienthoai' => ['required', 'regex:/^(0)(3[2-9]|5[6|8|9]|7[0|6-9]|8[0-6|8|9]|9[0-4|6-9])[0-9]{7}$/']
        ];
    }
    public function messages()
    {
        return [
            'email.required' => "Email Không Được Để Trống",
            'email.unique' => "Email Đã Tồn Tại",
            'email.min' => "Email Không Được Ít Hơn 3 Kí Tự",
            'email.max' => "Email Không Được Nhiều Hơn 100 Kí Tự",
            'ten.required' => "Họ tên không Được Để Trống",
            'ten.max' => "Họ tên không quá 100 kí tự.",
            'sodienthoai.required' => "Số điện thoại không để trống",
            'sodienthoai.regex' => "Số điện thoại đúng."
        ];
    }
}