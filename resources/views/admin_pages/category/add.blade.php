@extends('templates.admins.layout')
@section('content')
    <div class="title-add">
        <h3>THÊM THỂ LOẠI SẢN PHẨM</h3>
    </div>
    {{-- @if (Session::has('errors_add'))
        <div class="alert alert-danger" style="font-size:24px"> {{ Session::get('errors_add') }}</div>
    @endif --}}
    <div class="content-add">
        <form action="{{ route('categories.addhandle') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-add-material-l">
                <div class="form-group">
                    <label for="">Tên loại</label>
                    <input type="text" name="namecategory">
                </div>
            </div>
            <div class="form-add-material-l">
                <div class="form-group">
                    <label for="">Mô tả</label>
                    <input type="text" name="descriptioncategory">
                </div>
            </div>
            <div class="form-add-material-l">
                <div class="form-group">
                    <label for="">Hình ảnh</label>
                    <input type="file" name="categoriesIMG" id="categoriesIMG" class="form-control"
                        onchange="preview_image_add()">
                    @if ($errors->first('categoriesIMG'))
                        <div class="btn-danger">
                            {{ $errors->first('categoriesIMG') }}
                        </div>
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-success">Lưu thông tin</button>
        </form>
    </div>
@endsection
