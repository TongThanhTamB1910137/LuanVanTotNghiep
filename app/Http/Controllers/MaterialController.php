<?php

namespace App\Http\Controllers;

use App\Models\Materials;
use App\Models\MaterialUnit;
use DateTime;
use Illuminate\Support\Str;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class MaterialController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function show()
    {
        $nglieu = Materials::paginate(10);
        return view('admin_pages.material.index', compact('nglieu'));
    }

    //add new material
    public function add()
    {
        $dv_nglieu = MaterialUnit::all();
        return view('admin_pages.material.add', compact('dv_nglieu'));
    }


    public function create(Request $req)
    {
        //validate values input from form add
        $req->validate([
            'MaterialName' => 'required|max:255',
            'MaterialImage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:100000',
            'MaterialQuantily' => 'required|integer|min:0',
            'ImportPrice' => 'required|integer|min:0',
            'ExpiredDate' => 'required',
        ]);

        $slug = Str::slug($req->MaterialName);
        if ($this->checkNameMalExisted($slug)) {
            $dv_nglieu = MaterialUnit::all();
            session()->put('error_nameexists', true);
            return view('admin_pages.material.add', compact('dv_nglieu'));
        } else {
            $imageName = $this->uploadImage($req);
            $nglieu = new Materials();
            $nglieu->slug = $slug;
            $nglieu->ten_nglieu = $req->MaterialName;
            $nglieu->gia_nhap = $req->ImportPrice;
            $nglieu->so_luong = $req->MaterialQuantily;
            //format date to timestamp
            $timecurr = new DateTime();
            $nglieu->ngay_nhap = $timecurr->getTimestamp();
            $tam = $req->ExpiredDate;
            $date = new DateTime($tam);

            $nglieu->ngay_het_han = $date->getTimestamp();
            $nglieu->hinh_anh = $imageName;
            $nglieu->don_vi_nglieu = $req->input('select_unit');

            if ($date->getTimestamp() - $timecurr->getTimestamp() < 0) {
                session()->put('error_date',true);
                return redirect()->back();
            }
            $nglieu->save();
            session()->put('success_add_mal', 'them thanh cong');
        }
        return redirect('admin/nguyen-lieu');
    }



    // public function addMaterialViewAjax()
    // {
    //     $dv_nl = MaterialUnit::all();
    //     return response()->json([
    //         'dv_ngl' => $dv_nl,
    //     ]);
    // }
    // public function addMaterialHandleAjax(Request $request)
    // {
    //     $newMal = new Materials();
    //     $newMal->ten_nglieu = $request->ten_nl;
    //     // $newMal->slug = Str::slug($request->ten_nl);
    //     $newMal->gia_nhap = $request->gia_nhap;
    //     $newMal->don_vi_nglieu = $request->don_vi_nglieu;
    //     $timecurr = new DateTime();
    //     $newMal->ngay_nhap = $timecurr->getTimestamp();
    //     $newMal->so_luong = $request->so_luong;
    //     $newMal->ngay_het_han = $request->ngay_het_han;
    //     $newMal->save();
    //     $checkInsert = Materials::where('ten_nglieu', $request->ten_nl)->get();
    //     if ($checkInsert != null) {
    //         return response()->json(
    //             [
    //                 'result_insert' =>  "success"
    //             ]
    //         );
    //     }
    //     return response()->json(
    //         [
    //             'result_insert' =>  "fail"
    //         ]
    //     );
    // }

    // public function delMalAjax($id)
    // {
    //     $del = Materials::findOrFail($id);
    //     $del->delete();
    //     return response()->json([
    //         'msg' => 'xoa thhanh cong'
    //     ]);
    // }

    //update material
    public function edit($slug)
    {
        $slug_mal = $slug;
        $nglieu = Materials::where('slug', $slug_mal)->first();
        $dv_nglieu = MaterialUnit::all();
        $timeexp = $nglieu->ngay_het_han;
        // echo $timeexp;
        $timein = $nglieu->ngay_nhap;
        $fm_date_expi = date('Y-m-d', $timeexp);
        $fm_date_in = date('Y-m-d', $timein);
        // echo $slug;
        return view('admin_pages.material.edit', compact('nglieu', 'dv_nglieu', 'fm_date_expi', 'fm_date_in'));
    }

    public function update(Request $req)
    {
        $req->validate([
            'ten_nglieu' => 'required|max:255',
            'MaterialImage' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:100000',
            'so_luong' => 'required|integer|min:0',
            'gia_nhap' => 'required|integer|min:0',
            'ngay_nhap' => 'required',
        ]);

        $nglieu = Materials::findOrFail($req->id);
        $nameMal = "";
        $oldNameMal = $nglieu->ten_nglieu;
        $newNameMal = $req->ten_nglieu;
        if ($newNameMal == $oldNameMal) {
            $nameMal = $oldNameMal;
        } else {
            $nameMal = $newNameMal;
            if ($this->checkNameMalExisted(Str::slug($nameMal))) {
                session()->put('error_nameexists', true);
                return redirect()->back();
            }
        }
        $nglieu->ten_nglieu = $nameMal;
        $nglieu->gia_nhap = $req->gia_nhap;
        $nglieu->so_luong = $req->so_luong;

        if ($req->MaterialImage != null) {
            $imageName = $this->uploadImage($req);
        } else {
            $imageName = $req->imageOld;
        }
        $tam = $req->dateEXP;
        $date = new DateTime($tam);
        $nglieu->ngay_het_han = $date->getTimestamp();
        $tamin = $req->dateIn;
        $datein = new DateTime($tamin);
        $nglieu->ngay_nhap = $datein->getTimestamp();
        if ($date->getTimestamp() - $datein->getTimestamp() < 0) {
            session()->put('error_timeexp', "Ngày hết hạn nhỏ hơn ngày nhập");
            return redirect()->back();
        }
        $nglieu->hinh_anh = $imageName;
        $nglieu->don_vi_nglieu = $req->select_unit;
        $nglieu->save();
        session()->put('success_edit_mal', 'cap nhat thanh cong');
        return redirect('admin/nguyen-lieu');
    }

    public function searchMaterial(Request $req)
    {
        $keySearch = $req->search;
        $nglieu = Materials::where('ten_nglieu', 'like', '%' . $keySearch . '%')->get();
        return view('admin_pages.material.index', compact('nglieu'));
    }

    //delete material
    public function delMaterial($id)
    {
        $malDel = Materials::where('id', $id)->first();

        $image_path = "uploads/materials/" . $malDel->hinh_anh;
        if (file_exists($image_path)) {
            @unlink(public_path($image_path));
        }
        $malDel->delete();

        $checkDel = Materials::where('id', $id)->first();
        if ($checkDel != null) {
            session()->put('error_del_mal', 'xoa nguyen lieu that bai!');
        } else {
            session()->put('success_del_mal', 'xoa nguyen lieu thanh cong!');
        }
        return redirect('admin/nguyen-lieu');
    }

    public function checkNameMalExisted($slug)
    {
        $mal = Materials::where('slug', $slug)->get('slug');
        $checkName = "";
        foreach ($mal as $m) {
            $checkName = $m->slug;
        }
        if ($slug == $checkName) {
            return true;
        }
        return false;
    }

    public function uploadImage($req)
    {
        $imageName = "";
        $images = $req->file('MaterialImage');
        if ($req->hasFile('MaterialImage')) {
            $images = $req->file('MaterialImage');
            $imageName = time() . '.' . $images->extension();
            $images->move(public_path('uploads/materials'), $imageName);
        }
        return $imageName;
    }
}