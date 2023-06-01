@extends('templates.admins.layout')
@section('content')
    <div class="title-add">
        <h3>THÊM NGUYÊN LIỆU SỬ DỤNG</h3>
    </div>
    <div class="show-err">
        @if (Session::has('errors_add'))
            <div class="alert alert-danger" style="font-size:24px"> {{ Session::get('errors_add') }}</div>
        @endif
        @if (session('loisoluong'))
            <h4 style="color: red">{{ Session::get('loisoluong') }}</h4>
        @endif
        @if (session('loi_ten_ton_tai'))
            <h4 style="color: red">{{ Session::get('loi_ten_ton_tai') }}<h4>
        @endif
    </div>
<?php  //dd(session()->all());?>
    {{ Session::forget('loisoluong') }}
    {{ Session::forget('loi_ten_ton_tai') }}
    {{ Session::forget('errors_add') }}

    <div class="content-add">
        <form action="{{ route('mmu.addhandle') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-add-material-l">
                <div class="form-group">
                    <label for="">Tên nguyên liệu sử dụng</label>
                    <input type="text" name="namemmu" id="namemmu" value="{{ old('namemmu') }}">
                    <br>
                    @if ($errors->first('namemmu'))
                        <div class="btn-danger">
                            {{ $errors->first('namemmu') }}
                        </div>
                    @endif
                </div>

                <div class="show-name-mal">

                </div>
            </div>
            <div class="form-add-material-l">
                <div class="form-group">
                    <label for="">Số lượng</label>
                    <input type="number" name="quantymmu" value="{{ old('quantymmu') }}">
                    <br>
                    <br><br>
                    @if ($errors->first('quantymmu'))
                        <div class="btn-danger">
                            {{ $errors->first('quantymmu') }}
                        </div>
                    @endif
                </div>

            </div>
            <button type="submit" class="btn btn-success">Lưu</button>
        </form>
    </div>
@endsection
