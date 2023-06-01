@extends('templates.admins.layout')
@section('content')
    
    <div class="infologin" style="font-weight: bold">

        <div class="title-show">
            <h3 style="font-size:18px">Đổi mật khẩu</h3>
        </div>
        <form action="{{ route('changepass') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="">Nhập mật khẩu cũ </label>
                <input type="password" name="oldpass" id="oldpass" maxlength="15" minlength="10" required>
            </div>
            <div class="form-group">
                <label for="">Nhập mật khẩu mới </label>
                <input type="password" name="newpass" id="newpass" maxlength="15" minlength="10" required>
            </div>
            <button type="submit" class="btn btn-success">Lưu</button>
        </form>

    </div>
@endsection
