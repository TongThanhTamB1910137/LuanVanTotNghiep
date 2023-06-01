<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestLogin extends FormRequest
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
            'taikhoan' => 'required',
            'password' => 'required'
        ];
        return $rules;
    }
        public function messages(){
        return [
            'taikhoan.required' => "Tài Khoản Không Được Để Trống",
            'password.required' => "Mật Khẩu Không Được Để Trống",
        ];
    }
}
