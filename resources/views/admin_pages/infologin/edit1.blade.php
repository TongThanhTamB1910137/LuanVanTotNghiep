@extends('templates.admins.layout')
@section('content')
    <div class="title-edit-show">
        <h3>CẬP NHẬT THÔNG TIN</h3>
    </div>

    <div class="content-edit-show showind">
        <form action="{{ route('updateinfo.handle', $staff->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="">
                <div class="form-edit-left">
                    <input type="text" name="id_nv" id="id_nv" value="{{ $staff->id }}" hidden>
                    <div class="form-group">
                        <label for="">Tên nhân viên</label>
                        <input type="text" name="ten" id="ten" value="{{ $staff->name_staff }}">
                        @if ($errors->first('ten'))
                            <div class="btn-danger">
                                {{ $errors->first('ten') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" id="email" value="{{ $staff->email }}">
                        @if ($errors->first('email'))
                            <div class="btn-danger">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Số điện thoại</label>
                        <input type="text" name="dienthoai" id="dienthoai" value="{{ $staff->phone_number }}">
                        @if ($errors->first('dienthoai'))
                            <div class="btn-danger">
                                {{ $errors->first('dienthoai') }}
                            </div>
                        @endif
                    </div>
            
                    <button type="submit" class="btn btn-success" style="margin-left: 200px">Lưu thay đổi</button>
        </form>

    </div>
@endsection
