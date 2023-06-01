<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestCoupon;
use App\Models\Categories;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product_Coupon;
use App\Models\Products;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    protected $url = 'admin_pages.coupon.';
    function index()
    {
        $ldate = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
        $upCoupon = Coupon::where('ngaykt', '<', $ldate)->update(['trangthai' => 2]);
        $coupon = Coupon::where('trangthai', '!=', -1)->get();
        $viewData = [
            'coupon' => $coupon,
        ];
        return view($this->url . 'index', $viewData);
    }

    function add()
    {
        $category = Categories::where('trangthai', 1)->get();
        $product = Products::where('trangthai', 1)->get();
        $viewData = [
            'category' => $category,
            'product' => $product,
        ];
        return view($this->url . 'add', $viewData);
    }

    function post(RequestCoupon $request)
    {
        $data['ten'] = $request->ten;
        $data['mota'] = $request->mota;
        $data['code'] = $request->code;
        $data['ngaybd'] = $request->ngaybd;
        $data['ngaykt'] = $request->ngaykt;
        $data['giamgia'] =  $request->giamgia;
        $data['loaigiam'] =  $request->loaigiam;
        $data['dieukien'] =  $request->loaikm;
        $img = $request->file('hinhanh');
        if ($img) {
            $newImage = rand(1, 9999999) . '.' . $img->getClientOriginalExtension();
            $img->move('uploads/coupon', $newImage);
            $data['hinhanh'] = $newImage;
        }
        $data['trangthai'] = 1;
        $data['hienthi'] = $request->loaigiam === 1 ? 1 : 0;


        $coupon = Coupon::create($data);
        if (+$coupon->dieukien === 1 && $request->id_products) {
            foreach ($request->id_products as $value) {
                $data['id_coupon'] = $coupon->id;
                $data['id_product'] = $value;
                Product_Coupon::create($data);
            }
        }

        return Redirect()->route('get.admin.coupon')->with('message', 'Đã thêm');
    }
    function delete($id)
    {
        $coupon = Coupon::find($id);
        if ($id) {
            if (count(OrderDetail::where('giagoc', $coupon->id)->get()) > 0 || count(Order::where('id_coupon', $coupon->id)->get())) {
                $coupon->trangthai = -1;
                $coupon->save();
                return redirect()->back();
            }
            Product_Coupon::where('id_coupon', $id)->delete();
            $coupon->delete();
        }

        return redirect()->back();
    }

    public function detailCoupon($id)
    {
        $coupon = Coupon::find($id);
        $produs_coupon = Product_Coupon::where('id_coupon', $coupon->id)->get();
        $cate = Categories::where('trangthai', 1)->get();
        $array = array();
        foreach ($cate as $value) {
            $products = array();
            foreach ($produs_coupon as $val) {
                if ($val->getProduct->id_loaisanpham === $value->id) {
                    array_push($products, $val->getProduct);
                }
            }

            if (count($products) > 0) {
                array_push($array, ['cate' => $value, 'products' => $products]);
            }
        }
        return view($this->url . 'view', ['coupon' => $coupon, 'array' => $array]);
    }

    public function edit($id)
    {
        $coupon = Coupon::find($id);
        $cate = Categories::where('trangthai', 1)->get();
        $produs_coupon = Product_Coupon::where('id_coupon', $coupon->id)->get();
        $array = array();
        foreach ($cate as $value) {
            $products = array();
            foreach ($produs_coupon as $val) {
                if ($val->getProduct->id_loaisanpham === $value->id) {
                    array_push($products, $val->getProduct);
                }
            }

            if (count($products) > 0) {
                array_push($array, ['cate' => $value, 'products' => $products]);
            }
        }
        return view($this->url . 'edit', ['coupon' => $coupon, 'array' => $array]);
    }

    public function editpost($id, RequestCoupon $request)
    {
        $coupon = Coupon::find($id);
        $dateold = Carbon::parse($coupon->ngaykt)->format('Y-m-d');
        $datenow = Carbon::now('Asia/Ho_Chi_Minh')->format("Y-m-d");
        if ($dateold != $request->ngaykt && $request->ngaykt < $datenow) {
            return back()->withErrors(['ngaykt' => 'Ngày kêt thúc không nhỏ hơn ngày hiện tại']);
        }
        $coupon->ten = $request->ten;
        $coupon->mota = $request->mota;
        $coupon->code = $request->code;
        $coupon->ngaybd = $request->ngaybd;
        $coupon->ngaykt = $request->ngaykt;
        $coupon->giamgia =  $request->giamgia;
        $coupon->loaigiam =  $request->loaigiam;
        $coupon->dieukien =  $request->loaikm;
        $img = $request->file('hinhanh');
        if ($img) {
            $newImage = rand(1, 9999999) . '.' . $img->getClientOriginalExtension();
            $img->move('uploads/coupon', $newImage);
            $coupon->hinhanh = $newImage;
        }
        $data['trangthai'] = 1;
        $data['hienthi'] = $request->loaigiam === 1 ? 1 : 0;


        $coupon->save();
        if (+$coupon->dieukien === 1 && $request->id_products) {
            Product_Coupon::where('id_coupon', $coupon->id)->delete();
            foreach ($request->id_products as $value) {
                $data['id_coupon'] = $coupon->id;
                $data['id_product'] = $value;
                Product_Coupon::create($data);
            }
        }

        return Redirect()->route('get.admin.coupon')->with('message', 'Đã thêm');
    }

    public function getCategoryPromo()
    {
        $category = Categories::where('trangthai', 1)->get();
        $url = asset('uploads/type/');
        $result = '<thead><tr><th scope="col"><input type="checkbox" /></th><th>STT</th><th>Tên</th></tr></thead><tbody>';
        foreach ($category as $key => $value) {
            $result .= '<tr><td scope=" col"><input type="checkbox" /></><td>' . ($key + 1) . '</td><td style="font-size: 16px; text-transform: uppercase; font-weight: 600;"> <img class="img-type" src="' . $url . '/' . $value->hinhanh . '">' . $value->tenloai . '</td></tr>';
        }

        $result .= '</tbody>';
        return Response()->json($result);
    }

    public function getProductPromo()
    {
        $product = Products::where('trangthai', 1)->orderBy('id_loaisanpham')->get();
        $url = asset('uploads/product/');
        $result = '<thead><tr><th scope="col"><input type="checkbox" /></th><th>STT</th><th>Tên</th><th>Loại</th></tr></thead><tbody>';
        foreach ($product as $key => $value) {
            $result .= '<tr><td scope=" col"><input type="checkbox" /></><td>' . ($key + 1) . '</td><td style="font-size: 16px; text-transform: uppercase; font-weight: 600;"> <img class="img-type" src="' . $url . '/' . $value->hinhanh . '">' . $value->tensp . '</td><td style="text-transform: uppercase; font-weight: 600;">' . $value->danhmuc->tenloai . '</td></tr>';
        }

        $result .= '</tbody>';
        return Response()->json($result);
    }

    public function getListData()
    {
        $category = Categories::where('trangthai', 1)->get();
        $product = Products::where('trangthai', 1)->orderBy('id_loaisanpham')->get();
        foreach ($product as $index =>   $value) {
            $product[$index]->id_loaisanpham = ['tenloai' => $value->danhmuc->tenloai, 'id_loai' => $value->id_loaisanpham];
        }
        return Response()->json(['category' => $category, 'products' => $product]);
    }
    public function activeCoupon($id)
    {
        $slide = Coupon::find($id);
        $slide->trangthai = +!$slide->trangthai;

        $slide->save();
        return redirect()->back()->with('messageupdate', 'Đã cập nhật.');
    }
    public function showCoupon($id)
    {
        $slide = Coupon::find($id);
        $slide->hienthi = +!$slide->hienthi;

        $slide->save();
        return redirect()->back()->with('messageupdate', 'Đã cập nhật.');
    }
}