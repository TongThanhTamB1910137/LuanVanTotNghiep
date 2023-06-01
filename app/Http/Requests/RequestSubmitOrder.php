<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestSubmitOrder extends FormRequest
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
            'hoten' => 'required',
            'email' => 'required',
            'sodienthoai' => 'required',
            'ward' => 'required',
        ];
        return $rules;
    }
    public function messages()
    {
        return [
            'hoten.required' => "Không để trống thông tin khách hàng.",
            'email.required' => "Không để trống thông tin khách hàng.",
            'sodienthoai.required' => "Không để trống thông tin khách hàng.",
            'ward.required' => "Không để trống thông tin khách hàng.",
        ];
    }
}