<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Facade\FlareClient\Http\Response;
use PHPUnit\Framework\Constraint\Count;

class CouponController extends Controller
{
    function getCoupon()
    {
        $today = Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString();
        $coupon = Coupon::where('trangthai', 1)
            ->where('hienthi', 1)
            ->where('ngaykt', '>', $today)
            ->where('dieukien', '!=', 1)
            ->get();

        return response()->json($coupon);
    }

    function checkCoupon(Request $request)
    {
        $id = ($request->id);
        $code = ($request->code);
        $coupon = null;
        if ($id) {
            $coupon = Coupon::find($id);
        }
        if ($code) {
            $coupon = Coupon::where('code', $code)->first();
        }
        if ($coupon) {
            $couponSS = Session('coupon') ? Session('coupon') : null;
            if ($couponSS) {
                $request->session()->forget('coupon');
                $request->session()->put('coupon', $coupon);
            } else {
                $request->session()->put('coupon', $coupon);
            }

            $html = view('templates.clients.home.cart')->render();
            return Response()->json(['coupon' => $coupon, 'html' => $html]);
        } else {
            return Response()->json('Mã giảm giá không tồn tại');
        }
    }

    public function getAllPromotion()
    {
        $today = Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString();
        $promotion = Coupon::where('trangthai', 1)
            ->where('hienthi', 1)
            ->where('ngaykt', '>', $today)
            ->get();
        $viewData = [
            'promo' => $promotion
        ];
        return view('templates.clients.promotion.index', $viewData);
    }
}