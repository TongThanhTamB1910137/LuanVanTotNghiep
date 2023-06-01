<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Comments;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Products;

class ProductController extends Controller
{
    public function index()
    {
        $product = Products::where('trangthai', 1)->get();
        $danhmuc = Categories::where('trangthai', 1)->get();

        $banner = Image::where('trangthai', 1)
            ->where('loai', 'bannerProduct')
            ->first();
        $viewData = [
            'product' => $product,
            'danhmuc' => $danhmuc,
            'banner' => $banner
        ];
        return view('templates.clients.product.index', $viewData);
    }
    public function detail($slug, Request $request)
    {
        $meta = [];
        if ($slug) {
            $product = Products::where('slug', $slug)->first();
            $discount = 0;
            if (count($product->Coupon) > 0) {
                if ($product->Coupon[0]->loaigiam === 1) {
                    $discount = $product->giaban *  $product->Coupon[0]->giamgia / 100;
                } else {
                    $discount = $product->Coupon[0]->giamgia;
                }
            }
            $product->giaban = ($product->giaban - $discount < 0) ? 0 : $product->giaban - $discount;
            $comments = Comments::where('id_sanpham', $product->id)
                ->where('type', 'product')
                ->where('parent_id', 0)
                ->get();
            if ($product) {
                $related = Products::where('id_loaisanpham', $product->id_loaisanpham)->get();
                $meta['title'] = $product->tensp;
                $meta['description'] = $product->tensp;
                $meta['url'] = $request->url();
                $meta['image'] = asset('uploads/product/' . $product->hinhanh);
            }
            $viewData = [
                'product' => $product,
                'related' => $related,
                'comments' => $comments,
                'meta' => $meta,
            ];
        }
        return view('templates.clients.product.detail', $viewData);
    }

    public function search(Request $request)
    {
        if ($request->keyword) {
            $product = Products::where('tensp', 'like', '%' . $request->keyword . '%')
                ->Where('trangthai', 1)->get()->sortBy('id_loaisanpham');
            return view('templates.clients.product.search', ['products' => $product]);
        }
    }
}