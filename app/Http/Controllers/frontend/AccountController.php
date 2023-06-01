<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Products;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Polyfill\Intl\Idn\Resources\unidata\Regex;
use Throwable;

class AccountController extends Controller
{
    public function detail(Request $request)
    {
        $orerDetail = OrderDetail::where('id_donhang', $request->id)->get();
        $order = Order::find($request->id);
        if ($orerDetail) {

            $viewData = [
                'order' => $order,
                'orderDetail' => $orerDetail
            ];
            return view('templates.clients.account.detailOrder', $viewData);
        }
    }

    public function wishlist(Request $request, $id)
    {
        $product = Products::find($id);
        if (!$product) {
            return response(['message' => 'Không tồn tại sản phẩm']);
        }
        if (!get_user('customer', 'id')) {
            return response(['message' => 'Đăng nhập để thêm']);
        }
        $messge = "Thêm sản phẩm yêu thích thành công!";
        try {
            Wishlist::create([
                'id_sanpham' => $id,
                'id_khachhang' => get_user('customer', 'id')
            ]);
        } catch (Throwable $th) {
            $messge = 'Sản phẩm này đã thêm!';
        }

        return response(['message' => $messge]);
    }

    public function delwishlist(Request $request, $id)
    {
        $wishlist = Wishlist::where('id_sanpham', $id)
            ->where('id_khachhang', get_user('customer', 'id'))->first();
        if ($wishlist) {
            $wishlist->delete();
            $main = 'wishlist';
            return redirect()->route('get.infouser', 'wishlist');
        }
    }

    public function changePass()
    {
        return view('templates.clients.account.changepassword');
    }

    public function changePassPost(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.required' => "Mật khẩu không được bỏ trống.",
            'password_confirmation.required' => "Mật khẩu xác nhận không được để trống.",
            'password.confirmed' => "Mật Khẩu Không Trùng Nhau",
        ]);
        if (get_user('customer')) {
            Customer::where('id', get_user('customer'))->update(['password' => Hash::make($request->password)]);
            return redirect()->back()->with('changePassSuccess', 'Thay đổi mật khẩu thành công.');
        }

        return redirect()->back();
    }
}