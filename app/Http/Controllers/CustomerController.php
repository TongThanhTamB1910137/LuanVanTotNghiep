<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestAddCustomer;
use App\Models\Comments;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = Customer::all();
        $coupon = Coupon::where(['trangthai' => 1, 'loaigiam' => 2, 'hienthi' => 0])->get();
        return view('admin_pages.customer.index', ['customer' => $customer, 'coupon' => $coupon]);
    }

    public function delete($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            if (count(Order::where('id_khachhang', $customer->id)->get()) > 0) {
                $customer->trangthai = 2;
                $customer->save();
            } else {
                Comments::where('id_khachhang', $customer->id)->delete();
                Wishlist::where('id_khachhang', $customer->id)->delete();
                $customer->delete();
            };
        }
        return redirect()->back();
    }
    public function updateStatus($id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->trangthai = $customer->trangthai === 1 ? 2 : 1;
            $customer->save();
            return redirect()->back()->with('successStatus', 'Cập nhật thành công.');
        }
        return redirect()->back()->with('successStatus', 'Cập nhật không thành công.');
    }

    public function add()
    {
        return view('admin_pages.customer.add');
    }
    public function saveCustomer(RequestAddCustomer $request)
    {
        $data['ten'] = $request->ten;
        $data['email'] = $request->email;
        $data['sodienthoai'] = $request->sodienthoai;
        $data['diachi'] = $request->diachi;
        $data['password'] = Hash::make($request->password);
        Customer::create($data);
        return redirect()->route('show.customer');
    }
    public function getEditCustomer($id)
    {
        $customer = Customer::find($id);
        return view('admin_pages.customer.edit', ['customer' => $customer]);
    }
    public function saveEditCustomer($id, RequestAddCustomer $request)
    {
        $data = Customer::find($request->id);
        if ($data) {
            $data['ten'] = $request->ten;
            $data['sodienthoai'] = $request->sodienthoai;
            $data['diachi'] = $request->diachi;
            $data['password'] = Hash::make($request->password);
            $data->save();
        }
        return redirect()->route('show.customer');
    }

    public function sendmailCustomer(Request $request)
    {
        $request->validate([
            'coupon' => 'required',
            'checks' => 'required',
        ], [
            'coupon.required' => "Chưa chọn khuyến mãi.",
            'checks.required' => "Chưa chọn khách hàng.",
        ]);
        $customer = Customer::whereIn('id', $request->checks)->get();
        $coupon = Coupon::whereIn('id', $request->coupon)->get();
        $data = [];
        foreach ($customer as $value) {
            $data['email'][] = $value->email;
        }
        try {
            Mail::send('admin_pages.customer.couponmail', ['coupon' => $coupon], function ($email) use ($data) {
                $email->subject('Drinks - Web');
                $email->to($data['email'], 'Gửi khuyễn mãi khách hàng.');
            });
            return redirect()->back()->with('successSendMail', 'Đã gửi mail thành công.');
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('errorSendMail', 'Gửi mail thất bại.');
        }
    }
}