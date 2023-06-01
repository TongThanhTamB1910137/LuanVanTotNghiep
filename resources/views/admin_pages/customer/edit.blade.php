@extends('templates.admins.layout')
@section('content')
<div class="container-fluid coupon form_ql">
    <div class="card_1">
        <h3 class="card-title">Sửa thông tin thành viên</h3>
        <div class="action">
            <a href="{{ route('show.customer')}}" class="btn_add primary">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
                Quay lại
            </a>
        </div>
        <div class="form-submit">
            <form action="{{ route('save.edit.customer', $customer->id)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="form_group">
                            <label>Họ tên</label>
                            <input name="ten" value="{{$customer->ten}}" autocomplete='off' class="form_control" />
                        </div>
                        @if($errors->first('ten'))
                        <span class="error text-danger">{{ $errors->first('ten') }}</span>
                        @endif
                        <div class="form_group">
                            <label>Email</label>
                            <input name="email" value="{{$customer->email}}" readonly type="email" autocomplete='off'
                                class="form_control" />
                        </div>
                        @if($errors->first('email'))
                        <span class="error text-danger">{{ $errors->first('email') }}</span>
                        @endif
                        <div class="form_group">
                            <label>Số điện thoại</label>
                            <input type="number" value="{{$customer->sodienthoai}}" name="sodienthoai"
                                autocomplete='off' class="form_control" />
                        </div>
                        @if($errors->first('sodienthoai'))
                        <span class="error text-danger">{{ $errors->first('sodienthoai') }}</span>
                        @endif
                        <div class="form_group">
                            <label>Địa chỉ</label>
                            <input name="diachi" value="{{$customer->diachi}}" autocomplete='off'
                                class="form_control" />
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