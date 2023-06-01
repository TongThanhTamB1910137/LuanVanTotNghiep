<?php

namespace App\Http\Controllers;

use App\Models\ManagerStaff;
use App\Models\type_account;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        $getID = auth()->user()->id;
        $getLogin = User::where('id', $getID)->get('page_access');
        // $listRoles = "";
        // foreach ($getLogin as $g) {
        //     $listRoles = $g['page_access'];
        // }
        // $pieces = explode(",", $listRoles);
        // $roles = array();
        $getStaff = User::all();
        // return view('admin_pages.managerpermission.index', compact('getStaff'));
        // foreach ($pieces as $p) {
        //     if ($p == 6) {
        //         $getStaff = User::all();
        //         return view('admin_pages.managerpermission.index', compact('getStaff'));
        //     }
        // }
        return view('admin_pages.managerpermission.index', compact('getStaff'));


        return view('admin_pages.denyaccess.index');
    }

    public function addview()
    {
        return view('admin_pages.managerpermission.add');

        $getID = auth()->user()->id;
        $getLogin = User::where('id', $getID)->get('roles_id');
        $listRoles = "";
        foreach ($getLogin as $g) {
            $listRoles = $g['roles_id'];
        }
        $pieces = explode(",", $listRoles);
        $roles = array();
        foreach ($pieces as $p) {
            if ($p == 4) {
                return view('admin_pages.managerpermission.add');
            }
        }
        return view('admin_pages.managerpermission.add');

        return view('admin_pages.denyaccess.index');
    }

    public function addhandle(Request $request)
    {

        //validate values input from form add
        $request->validate([
            'email' => 'required|email|max:255',
            'ten' => 'required|max:255',
            'matkhau' => 'required|min:10|max:15',
            'dienthoai' => 'required|max:10',
        ]);

        $newStaff = new User();
        $newStaff->email = $request->email;
        $newStaff->name_staff = $request->ten;
        $newStaff->password = bcrypt($request->matkhau);
        $newStaff->phone_number = $request->dienthoai;
        $listRole = "demo";
        // foreach ($request->roles as $val) {
        //     $listRole .= $val . ',';
        // }
        $listPage = "demo";
        // foreach ($request->choosepage as $val) {
        //     $listPage .= $val . ',';
        // }
        $newStaff->roles_id = $listRole;
        $newStaff->page_access = $listPage;
        $newStaff->type_account = $request->typeaccount;
        if ($this->checkMailExs($request->email)) {
            session()->put('add_staff_fail', "that bai! email nay da ton tai");
            return view('admin_pages.managerpermission.add');
        } else {
            $newStaff->save();
            session()->put('add_staff_success', "them thanh cong!");
        }

        $getStaff = User::all();
        return view('admin_pages.managerpermission.index', compact('getStaff'));
    }
    public function checkMailExs($mail)
    {
        # code...
        $getmail = User::where('email', $mail)->get();
        if ($getmail->count() == 0) {
            return false;
        }
        return true;
    }

    public function delstaff($id)
    {
        $getdel = User::where('id', $id)->first();
        $getdel->delete();
        $checkdel = User::where('id', $id)->first();
        if($checkdel==null){
            session()->put('status_delstaff', true);
        }
        return redirect('admin/phan-quyen');
    }
    public function edit($id)
    {
        $staff = User::where('id', $id)->first();
        $typeAcc = type_account::all();
        return view('admin_pages.managerpermission.edit', compact('staff', 'typeAcc'));
    }
    public function update(Request $req)
    {
        $req->validate([
            'email' => 'required|email|max:255',
            'ten' => 'required|max:255',
            'dienthoai' => 'required|max:10',
        ]);

        $id = $req->id_nv;
        $getStaff = User::find($id);
        $getStaff->name_staff = $req->ten_nv;
        $getStaff->email = $req->email_nv;
        $getStaff->phone_number = $req->sdt_nv;
        $getStaff->type_account = $req->typeaccount;
        $getStaff->save();
        session()->put('update_success', true);
        return redirect('admin/phan-quyen');
    }
    public function editinfo()
    {
        $id=Auth::user()->id;
        $staff = User::where('id', $id)->first();
        return view('admin_pages.infologin.edit1', compact('staff'));
    }
    public function updateinfo(Request $req)
    {
        $req->validate([
            'email' => 'required|email|max:255',
            'ten' => 'required|max:255',
            'dienthoai' => 'required|max:10',
        ]);

        $id = $req->id_nv;
        $getStaff = User::find($id);
        $getStaff->name_staff = $req->ten;
        $getStaff->email = $req->email;
        $getStaff->phone_number = $req->dienthoai;
        $getStaff->save();
        session()->put('update_success', true);
        return redirect('admin/phan-quyen');
    }
}
