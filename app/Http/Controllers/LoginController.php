<?php

namespace App\Http\Controllers;

use App\Mail\resetPassword;
use App\Mail\sendMail;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function getLogin()
    {
        return view('auths.login');
    }

    public function logout()
    {
        Auth::logout();
        return view('auths.login')->with("message", "Logout successful");
    }
    public function postLogin(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            session()->forget('error_login');
            return redirect()->route('showDashboard');
        }
        session()->put('error_login', 'Vui lòng kiểm tra lại email hoặc mật khẩu');

        return view('auths.login');
    }
    public function resetPasswordview()
    {
        return view('admin_pages.resetpassword.index');
    }
    public function sendMailReset(Request $req)
    {
        $mailable = new resetPassword($req->emailreset);
        Mail::to($req->emailreset)->send($mailable);
        return view('auths.login')->with("sendmailrs","vui lòng kiểm tra mail");
    }
    public function viewchangepassword($email)
    {
        return view('admin_pages.resetpassword.formrs', compact('email'));
    }
    public function handlerspw(Request $req)
    {
        $email = $req->emailrs;
        $getAc = User::where('email', $email)->first();
        $pw = $req->pw_inp;
        $repw = $req->repw_inp;
        if ($repw != $pw) {
            return view('admin_pages.resetpassword.formrs', compact('email'));
        }
        $getAc->password = bcrypt($pw);
        $getAc->save();

        $mailable = new sendMail($pw);
        Mail::to($email)->send($mailable);
        return view('auths.login');
    }
}
