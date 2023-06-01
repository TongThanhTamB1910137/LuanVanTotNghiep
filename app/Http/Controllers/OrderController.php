<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestSubmitOrder;
use App\Models\Cart;
use App\Models\Categories;
use App\Models\Products;
use App\Models\Customer;
use App\Models\District;
use App\Models\FeeShip;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\category;
use App\Models\Province;
use App\Models\Sale_statisticals;
use App\Models\Sizes;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Svg\Tag\Rect;

class OrderController extends Controller
{
    protected $url = 'admin_pages.order.';
    public function index($orderStatus = 'all', Request $request)
    {
        $order = null;
        switch ($orderStatus) {
            case 'receive':
                $order = Order::where('trangthai', 1);
                break;
            case 'cancel':
                $order = Order::where('trangthai', -1);
                break;
            case 'success':
                $order = Order::where('trangthai', 4)->orWhere('trangthai', 5);
                break;
            case 'process':
                $order = Order::whereNotIn('trangthai', [1, 4, -1, 5]);
                break;

            default:
                $order = Order::whereNotNull('id');
                break;
        }
        if ($request->content) {
            $order->where('hoten', 'like', '%' . $request->content . '%')
                ->orWhere('madh', 'like', '%' . $request->content . '%');
        }
        if ($request->payment && $request->payment != 10) {
            $order->where('trangthaithanhtoan', $request->payment);
        }
        if ($request->status && $request->status != 10) {
            $order->where('trangthai', $request->status);
        }

        $order = $order->orderByDesc('id')->paginate(5);
        $viewData = [
            'orderStatus' => $orderStatus,
            'order' => $order,
            'query' => $request->query()
        ];
        return view($this->url . 'index', $viewData);
    }

    public function del($id)
    {
        $order = Order::find($id);
        if ($order) {
            if ($order->httt == 2 || $order->httt == 3 || $order->httt == 1) {
                Payment::where('id_donhang', $order->id)->delete();
            }
            OrderDetail::where('id_donhang', $order->id)->delete();
            $order->delete();
        }
        return redirect()->back();
    }

    //lấy chi tiết đơn hàng 
    public function viewDetail($id, Request $request)
    {

        if ($request->ajax()) {
            $order = Order::find($id);
            $orderDetail = OrderDetail::where('id_donhang', $id)->get();
            $html = view($this->url . 'item_order', compact('orderDetail', 'order'))->render();
            return Response(['html' => $html]);
        }
    }

    public function action($action, $id)
    {
        $order = Order::find($id);
        if ($order) {
            switch ($action) {
                case 'receive':
                    $order->trangthai = 1;
                    break;
                case 'process':
                    $order->trangthai = 2;
                    break;

                case 'transport':
                    $order->trangthai = 3;
                    break;
                case 'success':
                    $order->trangthai = 4;
                    $order->trangthaithanhtoan = 1;
                    if (+$order->httt === 0) {
                        Sale_statisticals::where('id_don_hang', $order->id)->update(['trang_thai' => 1]);
                    }
                    break;

                case 'cancel':
                    $order->trangthai = -1;
                    break;
            }
            $order->save();
        }
        return redirect()->back();
    }

    public function calcelOrder(Request $request, $id)
    {
        if ($id && $request->reason) {
            $flag =  $this->sendMail($id, $request->reason);
            if ($flag === 1) {
                return redirect()->route('get.order', 'all')->with('message', 'Đã huỷ đơn hàng thành công.');
            }
        }
    }
    public function sendMail($id, $reason)
    {
        $orderMail = Order::find($id);
        if ($orderMail) {
            $orderDetail = OrderDetail::where('id_donhang', $orderMail->id)->get();
            $img_url = env('APP_URL_LINK');
            $viewData = [
                'img_url' => $img_url,
                'order' => $orderMail,
                'orderDetail' => $orderDetail,
                'reason' => $reason
            ];
            try {
                Mail::send('admin_pages.order.cancelOrder', $viewData, function ($email) use ($orderMail) {
                    $email->subject('Drinks - Web - Huỷ đơn hàng');
                    $email->to($orderMail->email, ($orderMail->hoten) ? $orderMail->hoten : "");
                });
                $orderMail->trangthai = -1;
                $orderMail->save();
            } catch (\Throwable $th) {
                return 0;
            }
        }
        return 1;
    }

    public function confirmOrder($id)
    {
        if ($id) {
            $order = Order::find($id);
            $order->trangthai = 5;
            $order->save();
        }
        return redirect()->back();
    }
    public function actionPayment($action, $id)
    {
        $order = Order::find($id);
        if ($order) {
            switch ($action) {
                case 'process':
                    $order->trangthaithanhtoan = 2;
                    break;
                case 'success':
                    $order->trangthaithanhtoan = 1;
                    break;
            }
            $order->save();
        }

        return redirect()->back();
    }



    //cập nhật đơn hàng 
    public function update($madh)
    {
        $order = Order::where('madh', $madh)->first();
        if ($order) {
            $orderDetail = OrderDetail::where('id_donhang', $order->id)->paginate(5);
            $payment = Payment::where('id_donhang', $order->id)->first();
            $viewData = [
                'order' => $order,
                'orderDetail' => $orderDetail,
                'payment' => $payment

            ];
            return view($this->url . 'edit', $viewData);
        }
    }

    public function print_order($madh)
    {
        $order = Order::where('madh', $madh)->first();
        if ($order) {
            $orderDetail = OrderDetail::where('id_donhang', $order->id)->get();
            $viewData = [
                'order' => $order,
                'orderDetail' => $orderDetail
            ];
            $file = $order->madh . '.pdf';
            $pdf = PDF::loadView($this->url . 'pdf', $viewData);
            return $pdf->stream($file);
        }
    }

    public function dels(Request $request)
    {
        $list = $request->checkdel;
        if ($list) {
            foreach ($list as $value) {
                $order = Order::find($value);
                if ($order) {
                    if ($order->httt == 2 || $order->httt == 3 || $order->httt == 1) {
                        Payment::where('id_donhang', $order->id)->delete();
                    }
                    OrderDetail::where('id_donhang', $order->id)->delete();
                    $order->delete();
                }
            }
        }
        return redirect()->back();
    }

    public function createOrder(Request $request)
    {
        if (Session('feeship')) {
            $request->session()->forget('feeship');
        }
        if (Session('cartAD')) {
            Session('cartAD')->feeShip = 0;
        }

        $pro = Province::all();
        $pro = FeeShip::whereNotNull('province_id')->get();
        $product = Products::where('trangthai', 1)->orderBy('id_loaisanpham')->get();
        $category = Categories::where('trangthai', 1)->get();
        foreach ($product as $value) {
            $product->size = $value->size;
            $product->danhmuc = $value->danhmuc;
        }
        return view($this->url . 'create', ['province' => $pro, 'product'  => ($product), 'category'  => $category]);
    }

    public function getCustomer()
    {
        $customer = Customer::where('trangthai', 1)->get();
        return response()->json(['customer' => $customer]);
    }

    public function createcart(Request $request)
    {
        if (Session('cartAD')) {
            $request->session()->forget('cartAD');
        }
        $inputData = $request->value;
        foreach ($inputData as $value) {
            $product = Products::find((int)$value['idProduct']);
            $discount = 0;
            if (count($product->Coupon) > 0) {
                if ($product->Coupon[0]->loaigiam === 1) {
                    $discount = $product->giaban *  $product->Coupon[0]->giamgia / 100;
                } else {
                    $discount = $product->Coupon[0]->giamgia;
                }
            }
            $product->giagoc = $product->giaban;
            $product->giaban = ($product->giaban - $discount < 0) ? 0 : $product->giaban - $discount;
            foreach ($value['listSize'] as $idSize) {
                if ($idSize) {
                    $size = Sizes::find((int)$idSize);
                    if ($product != null) {
                        $oldCart = Session('cartAD') ? Session('cartAD') : null;
                        $newCart = new Cart($oldCart);
                        $idCart = $product->id;
                        if ($oldCart) {
                            $idCart = $newCart->checkCartProduct($product->id, (int)$idSize, $oldCart);
                        }
                        $newCart->addCart($product, $idCart, 1, $size);
                        $request->session()->put('cartAD', $newCart);
                        if (Session('feeship')) {
                            Session('cartAD')->feeShip = (+Session('feeship')->feeship);
                        } else {
                            Session('cartAD')->feeShip = 0;
                        }
                    }
                }
            }
        }

        $html = view('admin_pages.order.itemCart')->render();
        return  Response()->json(['html' => $html]);
    }

    public function deleteCartAd(Request $request)
    {
        $keyCart = $request->keyCart;
        $oldCart = Session('cartAD') ? Session('cartAD') : null;
        $newCart = new Cart($oldCart);
        $newCart->deleteCart($keyCart);
        if (count($newCart->products) > 0) {
            $request->session()->put('cartAD', $newCart);
            if (Session('feeship')) {
                Session('cartAD')->feeShip = (+Session('feeship')->feeship);
            } else {
                Session('cartAD')->feeShip = 0;
            }
        } else {
            $request->session()->forget('cartAD');
        }
        $html = view('admin_pages.order.itemCart')->render();
        return  Response()->json(['html' => $html]);
    }

    public function upCartAd(Request $request)
    {
        $size = Sizes::find($request->size);
        $oldCart = Session('cartAD') ? Session('cartAD') : null;
        $newCart = new Cart($oldCart);
        $newCart->updateCart($request->key, $request->sl, $size);
        $request->session()->put('cartAD', $newCart);
        if (Session('feeship')) {
            Session('cartAD')->feeShip = (+Session('feeship')->feeship);
        } else {
            Session('cartAD')->feeShip = 0;
        }
        $html = view('admin_pages.order.itemCart')->render();
        return  Response()->json(['html' => $html]);
    }

    public function saveOrderAd(RequestSubmitOrder $request)
    {
        $cart = Session('cartAD') ? Session('cartAD') : null;
        if ($cart) {
            $donhang = new Order;
            $aray = ['DO', 'OD', 'DR', 'RD'];
            $madh = Arr::random($aray) . rand(10000, 99999);
            if ($request->id) {
                $donhang['id_khachhang'] = $request->id;
            }
            $donhang['id_nhanvien'] = getIdLog();
            $donhang['madh'] = $madh;
            $donhang['email'] = $request->email;
            $donhang['hoten'] = $request->hoten;
            $donhang['dienthoai'] = $request->sodienthoai;
            $P = Province::where('province_code', $request->province)->first(['province_name']);
            $D = District::where('district_code', $request->district)->first(['district_name']);
            $W = Ward::where('ward_code', $request->ward)->first(['ward_name']);
            $donhang['diachi'] = $request->diachi . ', ' . $W->ward_name . ', ' . $D->district_name . ', ' . $P->province_name;
            $donhang['tongdonhang'] = $cart->totalPrice;
            $donhang['tongtien'] = $cart->totalPrice;
            if (Session('feeship')) {
                $donhang['id_feeship'] = Session('feeship')->id;
                $donhang['tongtien'] = $donhang['tongtien'] + Session('feeship')->feeship;
            }
            $donhang['ngaytao'] = Carbon::now('Asia/Ho_Chi_Minh');
            $donhang['httt'] = 0;
            $donhang['trangthaithanhtoan'] = 0;
            $donhang->save();
            if ($donhang) {
                foreach ($cart->products as $value) {
                    //lưu sản phẩm đơn hàng
                    $data['id_donhang'] = $donhang->id;
                    $data['id_sanpham'] = $value['productInfo']->id;
                    $data['soluong'] = $value['quanty'];
                    $data['id_size'] = $value['size']->id;
                    $data['giaban'] = $value['price'];
                    $data['giagoc'] = null;
                    if (count($value['productInfo']->Coupon) > 0) {
                        $data['giagoc'] = $value['productInfo']->Coupon[0]->id;
                    }

                    OrderDetail::create($data);
                }
            }
            $request->session()->forget('cartAD');
            $request->session()->forget('feeship');
            return redirect()->route('get.order', 'receive');
        } else {
            $request->validate([
                'sanpham' => 'required',
            ], [
                'sanpham.required' => "Chưa chọn sản phẩm nào.",
            ]);
        }
    }

    public function checkProductExist(Request $request)
    {
    }
}