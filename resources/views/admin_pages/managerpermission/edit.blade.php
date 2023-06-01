@extends('templates.admins.layout')
@section('content')
    <div class="title-edit-show">
        <h3>SỬA NHÂN VIÊN</h3>
    </div>

    <div class="content-edit-show showind">
        <form action="{{ route('staff.edithandle', $staff->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="">
                <div class="form-edit-left">
                    <input type="text" name="id_nv" id="id_nv" value="{{ $staff->id }}" hidden>
                    <div class="form-group">
                        <label for="">Tên nhân viên</label>
                        <input type="text" name="ten_nv" id="ten_nv" value="{{ $staff->name_staff }}">
                        @if ($errors->first('ten_nv'))
                            <div class="btn-danger">
                                {{ $errors->first('ten_nv') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email_nv" id="email_nv" value="{{ $staff->email }}">
                        @if ($errors->first('email_nv'))
                            <div class="btn-danger">
                                {{ $errors->first('email_nv') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Số điện thoại</label>
                        <input type="text" name="sdt_nv" id="sdt_nv" value="{{ $staff->phone_number }}">
                        @if ($errors->first('sdt_nv'))
                            <div class="btn-danger">
                                {{ $errors->first('sdt_nv') }}
                            </div>
                        @endif
                    </div>
                    <div class="type-account">
                        <label for="">Chọn loại tài khoản</label>
                        <select name="typeaccount" id="">
                            @foreach ($typeAcc as $value)
                                @if ($value->id == $staff->type_account)
                                    <option value="{{ $value->id }}"selected="selected">{{ $value->type_account }}
                                    </option>
                                @else
                                    <option value="{{ $value->id }}"> {{ $value->type_account }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success" style="margin-left: 200px">Lưu thay đổi</button>
        </form>

    </div>
@endsection
