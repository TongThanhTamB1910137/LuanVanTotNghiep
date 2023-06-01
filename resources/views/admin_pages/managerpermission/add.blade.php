@extends('templates.admins.layout')
@section('content')
    <div class="title-show">
        <h3>Thêm tài khoản</h3>
    </div>
    @if (session('add_staff_fail'))
        {{ Session::get('add_staff_fail') }}
    @endif
    {{Session::forget('add_staff_fail')}}
<?php //dd(session()->all()); ?>

    <div class="add-staff" style="font-weight: bold">
        <form action="{{ route('roles.addstaff') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="">Email</label>
                <input type="text" name="email" id="email">
                @if ($errors->first('email'))
                    <div class="btn-danger">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">Tên nhân viên</label>
                <input type="text" name="ten" id="ten">
                @if ($errors->first('ten'))
                    <div class="btn-danger">
                        {{ $errors->first('ten') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">Mật khẩu</label>
                <input type="password" name="matkhau" id="matkhau" minlength="10" maxlength="15">
                @if ($errors->first('matkhau'))
                    <div class="btn-danger">
                        {{ $errors->first('matkhau') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label for="">Số điện thoại</label>
                <input type="text" name="dienthoai" id="dienthoai" minlength="10" maxlength="11">
                @if ($errors->first('dienthoai'))
                    <div class="btn-danger">
                        {{ $errors->first('dienthoai') }}
                    </div>
                @endif
            </div>
            {{-- <div class="form-group">
                <label>Quyen</label><br>
                <input type="checkbox" id="access" name="roles[]" value="1">
                <label for=""> Truy cap</label><br>
                <input type="checkbox" id="add" name="roles[]" value="2">
                <label for=""> Them</label><br>
                <input type="checkbox" id="del" name="roles[]" value="3">
                <label for=""> Xoa</label><br>
                <input type="checkbox" id="edit" name="roles[]" value="4">
                <label for=""> Sua</label><br><br>
            </div>
            <div class="form-group">
                <label for="">Chon trang</label><br>
                <input type="checkbox" id="pagedash" name="choosepage[]" value="1">
                <label for=""> Dashboard</label><br>
                <input type="checkbox" id="pagemal" name="choosepage[]" value="2">
                <label for=""> Nguyen lieu</label><br>
                <input type="checkbox" id="pagepro" name="choosepage[]" value="3">
                <label for=""> San pham</label><br>
                <input type="checkbox" id="pageorder" name="choosepage[]" value="4">
                <label for=""> Don Hang</label><br>
                <input type="checkbox" id="pagemmu" name="choosepage[]" value="5">
                <label for=""> Quan ly nguyen lieu dung</label><br>
                <input type="checkbox" id="pagerole" name="choosepage[]" value="6">
                <label for=""> Phan quyen</label><br><br>
            </div> --}}

            <div class="type-account">
                <label for="">Chọn loại tài khoản</label>
                <select name="typeaccount" id="">
                    <option value="2" selected>Nhân viên bán hàng</option>
                    <option value="3">Nhân viên pha chế</option>
                    <option value="4">Nhân viên thu ngân</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Lưu thông tin</button>
        </form>
    </div>
@endsection
