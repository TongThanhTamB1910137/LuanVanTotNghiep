<?php

namespace App\Http\Controllers;

use App\Models\District;    
use App\Models\FeeShip;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class ShippingController extends Controller
{
    protected $url = 'admin_pages.shipping.';
    function index()
    {

        $province = Province::all();
        $feeShip = FeeShip::where('parent', 0)->get();
        $viewData = [
            'province' => $province,
            'feeShip' => $feeShip
        ];
        return view($this->url . 'index', $viewData);
    }

    function post(Request $request)
    {
        if ($request->province_code && $request->feeship) {
            $data['province_id'] = $request->province_code;
            $data['feeship'] = $request->feeship;
            $data['trangthai'] = 1;
            $data['parent'] = 0;
            $kt = FeeShip::where('province_id', $data['province_id'])->get();
            if (!count($kt)) {
                FeeShip::create($data);
            }
        }
        return redirect()->back();
    }

    function change(Request $request)
    {
        $id_pro = json_decode($request->id_pro);
        $id_dis = json_decode($request->id_dis);
        $feeship = json_decode($request->feeship);
        $ward = json_decode($request->ward);


        if ($id_pro && $feeship) {
            FeeShip::where('province_id', $id_pro)
                ->update(['feeship' => $feeship]);
            $shipping = FeeShip::where('province_id', $id_pro)
                ->first();
            return Response()->json($shipping->feeship);
        }
        if ($id_dis && $feeship) {
            $fee = FeeShip::where('district_id', $id_dis)
                ->get();
            if ($fee->isEmpty()) {
                $data['district_id'] = $id_dis;
                $data['feeship'] = $feeship;
                $data['trangthai'] = 1;
                FeeShip::create($data);
            } else {
                FeeShip::where('district_id', $id_dis)
                    ->update(['feeship' => $feeship]);
            }
            $shipping = FeeShip::where('district_id', $id_dis)
                ->first();

            return Response()->json($shipping->feeship);
        }

        if ($ward && $feeship) {
            $fee = FeeShip::where('ward_id', $ward)
                ->get();

            if ($fee->isEmpty()) {
                $data['ward_id'] = $ward;
                $data['feeship'] = $feeship;
                $data['trangthai'] = 1;
                FeeShip::create($data);
            } else {
                FeeShip::where('ward_id', $ward)
                    ->update(['feeship' => $feeship]);
            }
            $shipping = FeeShip::where('ward_id', $ward)
                ->first();
            return Response()->json($shipping->feeship);
        }
    }


    function getWard($district)
    {
        $district = District::where('district_code', $district)->first();
        $viewData = [
            'district' => $district,
        ];
        return view($this->url . 'ward', $viewData);
    }


    function getPrice($id, Request $request)
    {

        $ward = Ward::where('ward_code', $id)->first();
        if ($ward) {

            $id_dis = District::where('district_code', $ward->district_code)->first();
            $id_pro = Province::where('province_code', $id_dis->province_code)->first();
            $price = FeeShip::where('ward_id', $ward->ward_code)->first();

            if ($price) {
            } else {
                $price = FeeShip::where('district_id', $id_dis->district_code)->first();

                if ($price) {
                } else {
                    $price = FeeShip::where('province_id', $id_pro->province_code)->first();
                }
            }
            $feeship = Session('feeship') ? Session('feeship') : null;
            if ($feeship) {
                $request->session()->forget('feeship');
                $request->session()->put('feeship', $price);
            } else {
                $request->session()->put('feeship', $price);
            }

            $html = view('templates.clients.home.cart')->render();
            return Response()->json($html);
        }
    }

    function delProvince($procode)
    {
        if ($procode) {
            $dist = FeeShip::whereNotNull('district_id')->get();
            foreach ($dist as $value) {
                $check = District::where('province_code', $procode)
                    ->where('district_code', $value->district_id)
                    ->get();
                if (count(($check)) > 0) {
                    FeeShip::where('district_id', $value->district_id)->delete();
                }
            }
            $w = FeeShip::whereNotNull('ward_id')->get();
            foreach ($w as $value) {
                $check = Ward::where('ward_code', $value->ward_id)
                    ->first();
                $district = District::where('district_code', $check->district_code)->first();
                if ($district->getProvince->province_code === $procode) {
                    FeeShip::where('ward_id', $check->ward_code)->delete();
                }
            }
            FeeShip::where('province_id', $procode)->delete();
        }

        return redirect()->back();
    }
}