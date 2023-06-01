<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestCoupon extends FormRequest
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
        if ($this->loaikm == 2) {
            $rules =  [
                'loaikm' => 'required',
                'ten' => 'required',
                'code' => 'required|unique:coupon,code,' . $this->id,
                'mota' => 'required',
                'ngaybd' => 'required',
                'ngaykt' => 'required',
                'giamgia' => 'required',
                'loaigiam' => 'required',
                'dieukien' => 'required'
            ];
        } else {
            $rules =  [
                'loaikm' => 'required',
                'ten' => 'required',
                'mota' => 'required',
                'ngaybd' => 'required',
                'ngaykt' => 'required',
                'giamgia' => 'required',
                'loaigiam' => 'required',
                'dieukien' => 'required'
            ];
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'loaikm.required' => "Không được để trống.",
            'code.required' => "Code không để trống.",
            'code.unique' => "Code đã tồn tại.",
            'ten.required' => "Tên không để trống.",
            'mota.required' => "Mô tả không để trống.",
            'ngaybd.required' => "Ngày bắt dầu không để trống.",
            'ngaykt.required' => "Ngày kết thúc không để trống.",
            'giamgia.required' => "Giá không để trống.",
            'loaigiam.required' => "Loại giảm không để trống.",
            'dieukien.required' => "Không được để trống.",

        ];
    }
}