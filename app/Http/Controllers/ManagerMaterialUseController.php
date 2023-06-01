<?php

namespace App\Http\Controllers;

use App\Models\ManagerMaterialUse;
use App\Models\Materials;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ManagerMaterialUseController extends Controller
{
    public function index()
    {
        $managerM = ManagerMaterialUse::paginate(20);
        $nameM = Materials::all();
        return view('admin_pages.managerMaterialUse.index', compact('managerM', 'nameM'));
    }

    public function sortMalByDay(Request $req)
    {
        $date = date_create($req->dateSort);
        $date_f = date_format($date, "Y-d-m");
        echo $req->dateSorto;
        // $managerM = ManagerMaterialUse::where('ngay_tong_ket', $req->dateSort);
        // $nameM = Materials::all();
        // return view('admin_pages.managerMaterialUse.index', compact('managerM', 'nameM'));
    }
    public function delMMU($id)
    {
        $getRecord = ManagerMaterialUse::find($id);
        $getRecord->delete();
        session()->put('delete_success', 'xoa thanh cong');
        return redirect()->back();
    }
    ///add new
    public function add()
    {
        return view('admin_pages.managerMaterialUse.add');
    }

    public function create(Request $request)
    {
        $request->validate([
            'namemmu' => 'required|max:255',
            'quantymmu' => 'required|integer|min:0'
        ]);

        $newmmu = new ManagerMaterialUse();
        $slug_name = Str::slug($request->namemmu);
        $newmmu->so_luong = $request->quantymmu;
        $timenow = Carbon::now('asia/Ho_Chi_Minh')->toDateString();
        $newmmu->ngay_tong_ket = $timenow;
        $getmmu = Materials::where('ten_nglieu', $request->namemmu)->first();
        $getData = Materials::where('ten_nglieu', $request->namemmu)->get();
        if ($getData->count() == 0) {
            session()->put('errors_add', 'nguyên liệu không tồn tại');
            return view('admin_pages.managerMaterialUse.add');
        } else {
            $newmmu->don_gia = $getmmu->gia_nhap;
            $newmmu->id_nguyen_lieu = $getmmu->id;
            session()->forget('add_mmu');
            //update quality material
            $qualityCurrent =  $getmmu->so_luong;
            if ($qualityCurrent < $request->quantymmu) {
                session()->put('loisoluong', 'số lượng nhập vào lớn hơn số lượng đang có');
                return view('admin_pages.managerMaterialUse.add');
            }
            if ($this->checknameExists($getmmu->id)) {

                session()->put('loi_ten_ton_tai', 'nguyên liệu này đã có trong bảng');
                return view('admin_pages.managerMaterialUse.add')->with('loi_ten_ton_tai', 'nguyên liệu này đã có trong bảng');
            }

            $newQuanty = $qualityCurrent - $request->quantymmu;
            $getmmu->so_luong = $newQuanty;
            $getmmu->save();
            $newmmu->save();
            $managerM = ManagerMaterialUse::paginate(20);
            $nameM = Materials::all();
        }


        return view('admin_pages.managerMaterialUse.index', compact('managerM', 'nameM'));
    }

    //edit
    public function edit($id)
    {
        $getmmu = ManagerMaterialUse::where('id', $id)->first();
        $getnameMal = Materials::where('id', $getmmu->id)->first();
        $namemal = $getnameMal->ten_nglieu;
        return view('admin_pages.managerMaterialUse.edit', compact('getmmu', 'namemal'));
    }
    public function update(Request $request)
    {
        $id = $request->id;
        $updatemmu = ManagerMaterialUse::where('id', $id)->first();
        $slug_mal = Str::slug($request->namemmu);
        // $updatemmu ->slug_name_mal=$slug_mal;
        $updatemmu->so_luong = $request->quantymmu;
        $getnamemal = Materials::where('id', $id)->first();
        if ($getnamemal == null) {
            session()->put('errors_add', 'nguyên liệu không tồn tại');
            return view('admin_pages.managerMaterialUse.add');
        }

        // if($this->checknameExists($request->namemmu)){
        //     return view('admin_pages.managerMaterialUse.add')->with('loi_ten_ton_tai','nguyên liệu này đã có trong bảng');
        // }
        $updatemmu->save();
        $managerM = ManagerMaterialUse::paginate(20);
        $nameM = Materials::all();
        session()->put('update_success', 'Thanh conng');

        return view('admin_pages.managerMaterialUse.index', compact('managerM', 'nameM'));
    }


    function checknameExists($id)
    {

        $getData = ManagerMaterialUse::where('id_nguyen_lieu', $id)->get();
        if ($getData->count() > 0) {
            return true;
        }
        return false;
    }
    function checkNameMal($name)
    {
        $getDT = Materials::where('ten_nglieu', $name)->get();
        if ($getDT->count() > 0) {
            return true;
        }
        return false;
    }

    public function turnover(Request $req)
    {
        $tienThu = 0;
        $date = date_create($req->datethongke);
        $date_f = date_format($date, "Y-d-m");
        $getDT = ManagerMaterialUse::where('ngay_tong_ket', $date_f)->get();
        $tienvatlieu = 0;
        foreach ($getDT as $val) {
            $tienvatlieu += $val->so_luong * $val->don_gia;
        }
        return $tienvatlieu;
    }
    public function searchmal(Request $request)
    {
        if ($request->ajax()) {
            $output = '';
            $products = DB::table('materials')->where('ten_nglieu', 'LIKE', '%' . $request->search . '%')->get();
            if ($products) {
                foreach ($products as $key => $product) {
                    $output .= '<h4><button id="choosenamemal type="button">' . $product->ten_nglieu . '</button></h4>';
                }
            }

            return Response($output);
        }
    }
}