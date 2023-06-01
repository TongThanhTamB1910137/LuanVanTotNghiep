@extends('templates.admins.layout')
@section('content')
    <div class="title-edit-show">
        <h3>sua nguyen lieu</h3>
    </div>

    <div class="content-edit-show">
        <form action="" method="post">
            @csrf
            <div class="form-edit-mal">
                <div class="form-edit-left">
                    <div class="form-group">
                        <label for="">ten nguyen lieu</label>
                        <input type="text" name="" id="" value="{{ $nglieu->ten_nglieu }}">
                    </div>
                    <div class="form-group">
                        <label for="">gia nhap</label>
                        <input type="text" name="" id="" value="{{ $nglieu->gia_nhap }}">
                    </div>
                    <div class="form-group">
                        <label for="">so luong</label>
                        <input type="text" name="" id="" value="{{ $nglieu->so_luong }}">
                    </div>
                    <div class="form-group">
                        <label for="">don vi</label>
                        <input type="text" name="" id="" value="{{ $nglieu->don_vi_nglieu }}">
                    </div>
                </div>
                <div class="form-edit-right">
                    <div class="show-img-mal" >
                        <img src="{{ asset('uploads/materials/' . $nglieu->hinh_anh)}}" alt="{{$nglieu->ten_nglieu}}" id="preview_images" style="width: 600px;height:300px">
                    </div>
                    <div class="form-group">
                        <input type="file" name="" id="" onchange="preview_image(this);">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success" style="margin-left: 200px">Luu</button>
        </form>
        
    </div>
@endsection
