<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetPassword extends FormRequest
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
            'password' => 'required|min:6||confirmed',
            'password_confirmation' => 'required',
        ];
    }
    public function messages(){
        return [
            'password.required' => "Mật Khẩu Không Được Để Trống",
            'password.min' => "Mật Khẩu Không Ít Hơn 6 Kí Tự",
            'password.confirmed' => "Mật Khẩu Không Trùng Nhau",
            'password_confirmation.required' => "Mật Khẩu Không Được Để Trống",
        ];
    }
}
