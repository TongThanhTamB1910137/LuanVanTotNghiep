<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlideRequest extends FormRequest
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
            'hinhanh' => 'required',
        ];
        return $rules;
    }
    public function messages()
    {
        return [
            'ten.required' => "Tên không để trống.",
            'hinhanh.required' => "Hình ảnh không để trống.",
        ];
    }
}