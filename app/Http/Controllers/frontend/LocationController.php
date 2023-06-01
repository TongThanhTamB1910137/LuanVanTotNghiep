<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    public function getProvince()
    {
        $province = Province::all();
        return Response()->json($province);
    }
    public function getDistrict($province)
    {
        $district = District::where('province_code', $province)->get();
        return Response()->json($district);
    }
    public function getWard($district)
    {
        $ward = Ward::where('district_code', $district)->get();
        return Response()->json($ward);
    }
}