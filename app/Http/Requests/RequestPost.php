<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestPost extends FormRequest
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
            'tieude' => 'required',
            'danhmuc' => 'required',
            'noidung' => 'required',
        ];
        return $rules;
    }
    public function messages()
    {
        return [
            'tieude.required' => "Không để trống tiêu đề.",
            'danhmuc.required' => "Không để trống danh mục.",
            'noidung.required' => "Không để trống nội dung.",
        ];
    }
}