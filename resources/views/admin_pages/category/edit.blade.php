@extends('templates.admins.layout')
@section('content')
    <div class="title-add">
        <h3>SỬA CATEGORY</h3>
    </div>
    {{-- @if (Session::has('errors_add'))
        <div class="alert alert-danger" style="font-size:24px"> {{ Session::get('errors_add') }}</div>
    @endif --}}
    <div class="content-add">
        <form action="{{route('categories.edithandle',$editCat->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="text" name="id_cat" value="{{$editCat->id}}" hidden>
            <div class="form-add-material-l">
                <div class="form-group">
                    <label for="">Tên loại</label>
                    <input type="text" name="categoryname_edit" value="{{$editCat->tenloai}}">
                </div>
            </div>
            <div class="form-add-material-l">
                <div class="form-group">
                    <label for="">Mô tả</label>
                    <input type="text" name="des_edit" value="{{$editCat->mota}}">
                </div>
            </div>
            <input type="text" name="oldename" id="" hidden value="{{$editCat->hinhanh}}">
            <div class="show-img-mal">
                <img src="{{ asset('uploads/categories/' . $editCat->hinhanh) }}" alt="{{ $editCat->tenloai }}"
                    id="preview_images" name="preview_images" style="width: 600px;height:300px">
            </div>
            <div class="form-group">
                <input type="file" name="categoryImage" id="categoryImage" onchange="preview_image(this)" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Luu</button>
        </form>
    </div>
@endsection
