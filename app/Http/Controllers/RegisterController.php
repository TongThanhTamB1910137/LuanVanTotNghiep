<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function showFormRegister()
    {
        return view('auths.register');
    }

    public function postRegister(Request $request)
    {
        // Kiểm tra dữ liệu vào
        $allRequest  = $request->all();
        if ($this->create($allRequest)) {
            // Insert thành công sẽ hiển thị thông báo
            Session::flash('success', 'Đăng ký thành viên thành công!');
            return redirect('register');
        }
        else {
            // Insert thất bại sẽ hiển thị thông báo lỗi
            Session::flash('error', 'Đăng ký thành viên thất bại!');
            return redirect('register');
        }
    }
    
    protected function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'level' => '3',
            'password' => bcrypt($data['password']),
        ]);
    }
}
