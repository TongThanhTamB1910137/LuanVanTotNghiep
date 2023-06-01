<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestContact extends FormRequest
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
            'email' => 'required',
            'ten' => 'required',
            'tieude' => 'required',
            'noidung' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'ten.required' => "Tên Không Được Để Trống",
            'email.required' => "Email không dược để trống.",
            'tieude.required' => "Tiêu đề Không Được Để Trống",
            'noidung.required' => "Nội dung Không Được Để Trống",
        ];
    }
}