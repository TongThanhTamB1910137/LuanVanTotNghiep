<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ChitietDH;
use App\Models\Comments;
use App\Models\Coupon;
use App\Models\District;
use Illuminate\Support\Facades\Mail;
use App\Models\Donhang;
use App\Models\Order;
use App\Models\Order_statisticals;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Products;
use App\Models\Sale_statisticals;
use App\Models\Province;
use App\Models\Sizes;
use App\Models\Ward;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use PhpParser\Node\Expr\FuncCall;
use Symfony\Component\HttpFoundation\Session\Session;

class CartController extends Controller
{
    public function addCart(Request $request)
    {
        $product = Products::find((int)$request->id);
        $discount = 0;
        if (count($product->Coupon) > 0) {
            if ($product->Coupon[0]->loaigiam === 1) {
                $discount = $product->giaban *  $product->Coupon[0]->giamgia / 100;
            } else {
                $discount = $product->Coupon[0]->giamgia;
            }
        }
        $product->giaban = ($product->giaban - $discount < 0) ? 0 : $product->giaban - $discount;
        $size = Sizes::find($request->size);
        if ($product != null) {
            $oldCart = Session('cart') ? Session('cart') : null;
            $newCart = new Cart($oldCart);
            $idCart = $request->id;
            if ($oldCart) {
                $idCart = $newCart->checkCartProduct($product->id, $request->size, $oldCart);
            }
            $newCart->addCart($product, $idCart, $request->sl, $size);
            $request->session()->put('cart', $newCart);
        }
        return view('templates.clients.home.cart');
    }
    public function delItemCart(Request $request)
    {
        $oldCart = Session('cart') ? Session('cart') : null;
        $newCart = new Cart($oldCart);
        $newCart->deleteCart($request->keyCart);
        if (count($newCart->products) > 0) {
            $request->session()->put('cart', $newCart);
        } else {
            $request->session()->forget('cart');
        }
        return view('templates.clients.home.cart');
    }

    public function index(Request $request)
    {
        if (Session('coupon')) {
            $request->session()->forget('coupon');
        }
        if (Session('feeship')) {
            $request->session()->forget('feeship');
        }
        return view('templates.clients.cart.index');
    }
    public function upCart(Request $request)
    {
        if ($request->keyCart) {
            $oldCart = Session('cart') ? Session('cart') : null;
            if ($oldCart) {
                $id = $oldCart->products[$request->keyCart]['productInfo']->id;
                $product = Products::find($id);
                $discount = 0;
                if (count($product->Coupon) > 0) {
                    if ($product->Coupon[0]->loaigiam === 1) {
                        $discount = $product->giaban *  $product->Coupon[0]->giamgia / 100;
                    } else {
                        $discount = $product->Coupon[0]->giamgia;
                    }
                }
                $product->giaban = ($product->giaban - $discount < 0) ? 0 : $product->giaban - $discount;
                $viewData = [
                    'keyCart' => $request->keyCart,
                    'product' => $product,
                    'sl' => $oldCart->products[$request->keyCart]['quanty'],
                    'size' => $oldCart->products[$request->keyCart]['size']->id
                ];

                return view('templates.clients.cart.updateCart', $viewData);
            }
        }
    }
    public function postupCart(Request $request)
    {
        $size = Sizes::find($request->size);
        $oldCart = Session('cart') ? Session('cart') : null;
        $newCart = new Cart($oldCart);

        $isExist = $newCart->checkProductUpdate($newCart, $request->id, $request->size, $request->key);
        if ($isExist) {
            $newCart->updateCart($request->key, $request->id, $size, $isExist);
        } else {
            $newCart->updateCart($request->key, $request->sl, $size);
        }
        $request->session()->put('cart', $newCart);
        return view('templates.clients.home.cart');
    }
    public function delCart(Request $request)
    {
        Mail::send('admin_pages.contact.email', ['data' => 'sdflksafjkds'], function ($email) {
            $email->subject('Drinks - Web');
            $email->to('trip6013@gmail.com', 'hi');
        });
        return 1;
    }

    public function InvoiceConfirm()
    {
        if (Session('cart')) {
            if (Session('feeship')) {
                Session('cart')->feeShip = (+Session('feeship')->feeship);
            } else {
                Session('cart')->feeShip = 0;
            }
            if (Session('coupon')) {

                if (+Session('coupon')->loaigiam === 1) {
                    Session('cart')->coupon = Session('cart')->totalPrice * Session('coupon')->giamgia / 100;
                    // Session('cart')->coupon = Session('cart')->totalPrice * Session('coupon')->giamgia / 100;
                    Session('cart')->discount = 'Giảm ' . Session('coupon')->giamgia . '%';
                } else {
                    Session('cart')->coupon = Session('coupon')->giamgia;
                    // Session('cart')->coupon = Session('cart')->totalPrice - Session('coupon')->giamgia;
                    Session('cart')->discount = 'Giảm ' . currency_format(Session('coupon')->giamgia);
                }
            } else {
                Session('cart')->coupon = 0;
            }
            // return view('templates.clients.cart.invoice');
            $html = view('templates.clients.cart.invoice')->render();
            return  Response()->json(['invoice' => $html]);
        }
    }

    //lưu đơn hàng
    public function postPay(Request $request)
    {
        $urlWebsite = asset('checkoutcomplete/');
        $cart = Session('cart') ? Session('cart') : null;
        if (Session('mDonHang')) {
            $request->session()->forget('mDonHang');
        }
        $donhang = new Order;

        if ($cart) {

            //mã đơn hàng
            $aray = ['DO', 'OD', 'DR', 'RD'];
            $madh = Arr::random($aray) . rand(10000, 99999);
            if (get_user('customer', 'id')) {
                $donhang['id_khachhang'] = get_user('customer', 'id');
            }
            $donhang['madh'] = $madh;
            $donhang['email'] = $request->email;
            $donhang['hoten'] = $request->name;
            $donhang['dienthoai'] = $request->phone;
            $P = Province::where('province_code', $request->province)->first(['province_name']);
            $D = District::where('district_code', $request->district)->first(['district_name']);
            $W = Ward::where('ward_code', $request->ward)->first(['ward_name']);
            $donhang['diachi'] = $request->address . ', ' . $W->ward_name . ', ' . $D->district_name . ', ' . $P->province_name;
            $donhang['ghichu'] = $request->note;
            $donhang['tongdonhang'] = $cart->totalPrice;
            $donhang['tongtien'] = $cart->totalPrice;
            if (Session('coupon')) {
                $donhang['id_coupon'] = Session('coupon')->id;
                if (Session('coupon')->loaigiam === 1) {
                    $donhang['tongtien'] = $donhang['tongtien'] - ($donhang['tongtien'] * Session('coupon')->giamgia / 100);
                } else {
                    $donhang['tongtien'] = $donhang['tongtien'] - Session('coupon')->giamgia;
                }
            }
            if (Session('feeship')) {
                $donhang['id_feeship'] = Session('feeship')->id;
                $donhang['tongtien'] = $donhang['tongtien'] + Session('feeship')->feeship;
            }
            $donhang['ngaytao'] = Carbon::now('Asia/Ho_Chi_Minh');
            $donhang['httt'] = $request->payment;
        } else {
            return redirect()->route('product');
        }
        $payment = $request->payment;
        switch ($payment) {
            case 1:
                # thanh toán paypal
                $provider = new PayPalClient;
                $provider->setApiCredentials(config('paypal'));
                $paypalToken = $provider->getAccessToken();

                $price = round($donhang['tongtien'] / 23187, 2);
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('successTransaction'),
                        "cancel_url" => route('cancelTransaction'),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => "USD",
                                "value" => $price
                            ]
                        ]
                    ]
                ]);
                if (isset($response['id']) && $response['id'] != null) {

                    // redirect to approve href
                    $request->session()->put('mDonHang', $donhang);
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()
                        ->route('get.cart')
                        ->with('error', 'Something went wrong.');
                } else {
                    return redirect()
                        ->route('get.cart')
                        ->with('error', $response['message'] ?? 'Thanh Toán Lỗi.');
                }
                break;
            case 2:
                # thanh toán momo
                $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";


                $partnerCode = 'MOMOBKUN20180529'; //MOMO
                $accessKey = 'klm05TvNBzhg7h7j'; //F8BBA842ECF85
                $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'; //K951B6PE1waDMi640xX08PD3vg6EkVlz
                $orderInfo = "Thanh toán qua MoMo";
                $amount = $donhang['tongtien'];
                $orderId = $donhang['madh'];  //time() . ""
                $redirectUrl = $urlWebsite;
                $ipnUrl = $urlWebsite;
                $extraData = "";
                $requestId = time() . "";
                $requestType = "captureWallet";
                //before sign HMAC SHA256 signature
                $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
                $signature = hash_hmac("sha256", $rawHash, $secretKey);
                $data = array(
                    'partnerCode' => $partnerCode,
                    'partnerName' => "Test",
                    "storeId" => "MomoTestStore",
                    'requestId' => $requestId,
                    'amount' => $amount,
                    'orderId' => $orderId,
                    'orderInfo' => $orderInfo,
                    'redirectUrl' => $redirectUrl,
                    'ipnUrl' => $ipnUrl,
                    'lang' => 'vi',
                    'extraData' => $extraData,
                    'requestType' => $requestType,
                    'signature' => $signature
                );
                $result = $this->execPostRequest($endpoint, json_encode($data));
                $jsonResult = json_decode($result, true);  // decode json

                //Just a example, please check more in there
                $request->session()->put('mDonHang', $donhang);
                return redirect()->to($jsonResult['payUrl']);
                break;
            case 3:
                # thanh toán vnpay
                $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
                $vnp_Returnurl = $urlWebsite;
                $vnp_Returnurl = $urlWebsite;
                $vnp_TmnCode = "PR66IZJ3"; //Mã website tại VNPAY 
                $vnp_HashSecret = "SOYGBHCVQDYTYQPIKKWFAETKMEVMZXUO"; //Chuỗi bí mật

                $vnp_TxnRef =  $donhang['madh']; //time() . ""; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
                $vnp_OrderInfo = 'Thanh toán đơn hàng VNPAY';
                $vnp_OrderType = '3';
                $vnp_Amount = $donhang['tongtien'] * 100;
                $vnp_Locale = 'vn';
                $vnp_BankCode = 'NCB'; //VNBANK VNPAYQR INTCARD
                $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
                $startTime = date('YmdHis');
                $vnp_ExpireDate = date('YmdHis', strtotime('+10 minutes', strtotime($startTime)));

                $inputData = array(
                    "vnp_Version" => "2.1.0",
                    "vnp_TmnCode" => $vnp_TmnCode,
                    "vnp_Amount" => $vnp_Amount,
                    "vnp_Command" => "pay",
                    "vnp_CreateDate" => date('YmdHis'),
                    "vnp_CurrCode" => "VND",
                    "vnp_IpAddr" => $vnp_IpAddr,
                    "vnp_Locale" => $vnp_Locale,
                    "vnp_OrderInfo" => $vnp_OrderInfo,
                    "vnp_OrderType" => $vnp_OrderType,
                    "vnp_ReturnUrl" => $vnp_Returnurl,
                    "vnp_TxnRef" => $vnp_TxnRef,
                    "vnp_ExpireDate" => $vnp_ExpireDate
                );

                if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                    $inputData['vnp_BankCode'] = $vnp_BankCode;
                }
                if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                    $inputData['vnp_Bill_State'] = $vnp_Bill_State;
                }
                ksort($inputData);
                $query = "";
                $i = 0;
                $hashdata = "";
                foreach ($inputData as $key => $value) {
                    if ($i == 1) {
                        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                    } else {
                        $hashdata .= urlencode($key) . "=" . urlencode($value);
                        $i = 1;
                    }
                    $query .= urlencode($key) . "=" . urlencode($value) . '&';
                }

                $vnp_Url = $vnp_Url . "?" . $query;
                if (isset($vnp_HashSecret)) {
                    $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
                    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                }
                $returnData = array(
                    'code' => '00', 'message' => 'success', 'data' => $vnp_Url
                );

                $request->session()->put('mDonHang', $donhang);
                return redirect()->to($vnp_Url);
                break;

            default:
                # thanh toán tiền mặt
                $cart = Session('cart') ? Session('cart') : null;
                if ($cart) {
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

                            $selectDT = Order_statisticals::where('id_san_pham_order', $value['productInfo']->id)->get();

                            // danh sach top san pham 
                            $product = Products::find($value['productInfo']->id);
                            if ($selectDT->count() == 0) {
                                $orderS = new Order_statisticals();
                                $orderS->id_san_pham_order = $product->id;
                                $orderS->so_luot_dat = $value['quanty'];
                                $orderS->save();
                            } else {
                                $orde = Order_statisticals::find($selectDT[0]->id);
                                $orde->so_luot_dat += $value['quanty'];
                                $orde->save();
                            }
                        }
                    }
                    $saleStatisticals = new Sale_statisticals();
                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $saleStatisticals->ngay_ban = date('Y-m-d', $request->responseTime);
                    $saleStatisticals->id_don_hang = $donhang->id;
                    $saleStatisticals->tien_don_hang = $donhang['tongtien'];
                    $saleStatisticals->trang_thai = 0;
                    $saleStatisticals->save();
                }
                $request->session()->forget('cart');
                return redirect()->route('checkoutcomplete', ['madh' => $donhang->madh]);
                break;
        }
        #endregion 
    }

    public function sendMail($madh)
    {
        $orderMail = Order::where('madh', $madh)->first();
        if ($orderMail) {
            $orderDetail = OrderDetail::where('id_donhang', $orderMail->id)->get();
            $img_url = env('APP_URL_LINK');
            $viewData = [
                'img_url' => $img_url,
                'order' => $orderMail,
                'orderDetail' => $orderDetail
            ];
            try {
                Mail::send('templates.clients.cart.mailOrder', $viewData, function ($email) use ($orderMail) {
                    $email->subject('Drinks - Web');
                    $email->to($orderMail->email, ($orderMail->hoten) ? $orderMail->hoten : "");
                });
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }

    public function checkoutComplete(Request $request)
    {
        if ($request->madh) {
            $this->sendMail($request->madh);
            return view('templates.clients.cart.checkoutComplete', ['madh' => $request->madh]);
        }
        $donhang = Session('mDonHang') ? Session('mDonHang') : null;
        if ($request->vnp_Amount && $request->vnp_ResponseCode == '00') {
            $cart = Session('cart') ? Session('cart') : null;
            $donhang['trangthaithanhtoan'] = 1;
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

                    $selectDT = Order_statisticals::where('id_san_pham_order', $value['productInfo']->id)->get();
                    $product = Products::find($value['productInfo']->id);
                    if ($selectDT->count() == 0) {
                        $orderS = new Order_statisticals();
                        $orderS->id_san_pham_order = $product->id;
                        $orderS->so_luot_dat = $value['quanty'];
                        $orderS->save();
                    } else {
                        $orde = Order_statisticals::find($selectDT[0]->id);
                        $orde->so_luot_dat += $value['quanty'];
                        $orde->save();
                    }
                }
            }
            $request->session()->forget('cart');
            $request->session()->forget('mDonHang');

            //lưu thanh toán
            $payment = new Payment;
            $payment->tongtien = $request->vnp_Amount / 100;
            $payment->mancc = $request->vnp_BankCode;
            $payment->loaithanhtoan = $request->vnp_CardType;
            $payment->sohoadon = $request->vnp_TxnRef;
            $payment->magiaodich = $request->vnp_TransactionNo;
            $payment->magiaodichBank = $request->vnp_BankTranNo;
            $payment->noidung = $request->vnp_OrderInfo;
            $payment->ngaythanhtoan = $request->vnp_PayDate;
            $payment->id_donhang = $donhang->id;
            $payment->save();

            $saleStatisticals = new Sale_statisticals();

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $saleStatisticals->ngay_ban = date('Y-m-d', $request->responseTime);
            $saleStatisticals->id_don_hang = $donhang->id;
            $saleStatisticals->tien_don_hang = $request->vnp_Amount / 100;
            $saleStatisticals->save();

            $this->sendMail($donhang->madh);
            return view('templates.clients.cart.checkoutComplete', ['madh' => $donhang->madh]);
        } else if ($request->partnerCode && $request->resultCode == 0) {
            $donhang = Session('mDonHang') ? Session('mDonHang') : null;
            $cart = Session('cart') ? Session('cart') : null;
            $donhang['trangthaithanhtoan'] = 1;
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

                    $selectDT = Order_statisticals::where('id_san_pham_order', $value['productInfo']->id)->get();
                    $product = Products::find($value['productInfo']->id);
                    if ($selectDT->count() == 0) {
                        $orderS = new Order_statisticals();
                        $orderS->id_san_pham_order = $product->id;
                        $orderS->so_luot_dat = $value['quanty'];
                        $orderS->save();
                    } else {
                        $orde = Order_statisticals::find($selectDT[0]->id);
                        $orde->so_luot_dat += $value['quanty'];
                        $orde->save();
                    }
                }
            }
            $request->session()->forget('cart');
            $request->session()->forget('mDonHang');

            //lưu thanh toán
            $payment = new Payment;
            $payment->tongtien = $request->amount;
            $payment->mancc = $request->partnerCode;
            $payment->loaithanhtoan = $request->payType;
            $payment->sohoadon = $request->orderId;
            $payment->magiaodich = $request->transId;
            $payment->noidung = $request->orderInfo;
            $payment->ngaythanhtoan = $request->responseTime;
            $payment->id_donhang = $donhang->id;
            $payment->save();

            $saleStatisticals = new Sale_statisticals();

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $saleStatisticals->ngay_ban = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
            $saleStatisticals->id_don_hang = $donhang->id;
            $saleStatisticals->tien_don_hang = $request->amount;
            $saleStatisticals->save();

            $this->sendMail($donhang->madh);
            return view('templates.clients.cart.checkoutComplete', ['madh' => $donhang->madh]);
        } else {
            return redirect()->route('get.cart');
        }
        if ($request->madh) {
            return view('templates.clients.cart.checkoutComplete', ['madh' => $request->madh]);
        }
        return redirect()->back();
    }


    //hàm thanh toán momo
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    //thanh toán paypal
    /**
     * success transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function successTransaction(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $donhang = Session('mDonHang') ? Session('mDonHang') : null;
            $cart = Session('cart') ? Session('cart') : null;
            $donhang['trangthaithanhtoan'] = 1;
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
            $request->session()->forget('cart');
            $request->session()->forget('mDonHang');
            if (Session('feeship')) {
                $request->session()->forget('feeship');
            }
            if (Session('coupon')) {
                $request->session()->forget('coupon');
            }
            //lưu thanh toán

            $payment = new Payment;
            $payment->tongtien = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            $payment->mancc = 'Paypal';
            $payment->loaithanhtoan = 'Paypal';
            $payment->sohoadon = $response['purchase_units'][0]['payments']['captures'][0]['id'];
            $payment->magiaodich = $response['id'];
            $payment->noidung = 'Thanh toán Paypal';
            $payment->ngaythanhtoan = $response['purchase_units'][0]['payments']['captures'][0]['create_time'];
            $payment->id_donhang = $donhang->id;


            $saleStatisticals = new Sale_statisticals();

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $saleStatisticals->ngay_ban = date('Y-m-d', $request->responseTime);
            $saleStatisticals->id_don_hang = $donhang->id;
            $saleStatisticals->tien_don_hang = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'] * 23187;
            $saleStatisticals->save();
            $payment->save();
            $this->sendMail($donhang->madh);
            return view('templates.clients.cart.checkoutComplete', ['madh' => $donhang->madh]);
        } else {
            return redirect()
                ->route('get.cart')
                ->with('error', $response['message'] ?? 'Thanh Toán Lỗi.');
        }
    }

    /**
     * cancel transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelTransaction(Request $request)
    {
        return redirect()
            ->route('get.cart')
            ->with('error', $response['message'] ?? 'Bạn Đã Huỷ Thanh Toán.');
    }
}