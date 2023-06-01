@extends('templates.admins.layout')
@section('content')
<div class="container-fluid coupon form_ql">
    <div class="card_1">
        <h3 class="card-title">Thêm thành viên</h3>
        <div class="action">
            <a href="{{ route('show.customer')}}" class="btn_add primary">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
                Quay lại
            </a>
        </div>
        <div class="form-submit">
            <form action="{{ route('get.save.customer')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="form_group">
                            <label>Họ tên</label>
                            <input name="ten" autocomplete='off' class="form_control" />
                        </div>
                        @if($errors->first('ten'))
                        <span class="error text-danger">{{ $errors->first('ten') }}</span>
                        @endif
                        <div class="form_group">
                            <label>Email</label>
                            <input name="email" type="email" autocomplete='off' class="form_control" />
                        </div>
                        @if($errors->first('email'))
                        <span class="error text-danger">{{ $errors->first('email') }}</span>
                        @endif
                        <div class="form_group">
                            <label>Số điện thoại</label>
                            <input type="number" name="sodienthoai" autocomplete='off' class="form_control" />
                        </div>
                        @if($errors->first('sodienthoai'))
                        <span class="error text-danger">{{ $errors->first('sodienthoai') }}</span>
                        @endif
                        <div class="form_group">
                            <label>Địa chỉ</label>
                            <input name="diachi" autocomplete='off' class="form_control" />
                        </div>
                        <div class="form_group">
                            <label>Mật khẩu mặc định : 123456</label>
                            <input name="password" hidden value="123456" class="form_control" />
                        </div>
                    </div>
                    <div class="col-12 action aciton_bottom">
                        <button type="submit" class="btn_add secondary">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            Lưu lại
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop