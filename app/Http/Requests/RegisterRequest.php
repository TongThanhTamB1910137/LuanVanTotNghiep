<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'required|min:3|max:100|unique:customers,email,'.$this->id,
            'password' => 'required|min:6||confirmed',
            'password_confirmation' => 'required',
        ];
    }
    public function messages(){
        return [
            'email.required' => "Email Không Được Để Trống",
            'email.unique' => "Email Đã Tồn Tại",
            'email.min' => "Email Không Được Ít Hơn 3 Kí Tự",
            'email.max' => "Email Không Được Nhiều Hơn 100 Kí Tự",
            'password.required' => "Mật Khẩu Không Được Để Trống",
            'password.min' => "Mật Khẩu Không Ít Hơn 6 Kí Tự",
            'password.confirmed' => "Mật Khẩu Không Trùng Nhau",
            'password_confirmation.required' => "Mật Khẩu Không Được Để Trống",
        ];
    }
}
