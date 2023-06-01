@extends('templates.admins.layout')
@section('content')
    <div class="title-edit-show">
        <h3>Sửa sản phẩm</h3>
    </div>

    {{ Breadcrumbs::render('Sửa sản phẩm', $spham->slug) }}
    <div class="content-edit-show">
        <form action="{{ route('products.edithandle', $spham->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-edit-mal editind">
                <div class="form-edit-left">
                    <input type="text" name="id_spham" id="id_spham" value="{{ $spham->id }}" hidden>
                    <div class="form-group">
                        <label for="">Tên sản phẩm</label>
                        <input type="text" name="ten_spham" id="ten_spham" value="{{ $spham->tensp }}" class="form-control">
                        @if ($errors->first('ten_spham'))
                        <div class="btn-danger">
                            {{ $errors->first('ten_spham') }}
                        </div>
                    @endif
                    </div>
                    <div class="form-group">
                        <label for="">Giá bán</label>
                        <input type="text" name="giaban" id="giaban" value="{{ $spham->giaban }}" class="form-control">
                        @if ($errors->first('giaban'))
                        <div class="btn-danger">
                            {{ $errors->first('giaban') }}
                        </div>
                    @endif
                    </div>
                    <div class="form-group">
                        <label for="">Loại sản phẩm</label>
                        <select name="select_cat">
                            @foreach ($catetype as $dvnl)
                                @if ($dvnl->id == $spham->id_loaisanpham)
                                    <option name="" id="" selected="selected" value="{{ $dvnl->id }}">
                                        {{ $dvnl->tenloai }}</option>

                                @else{
                                    <option name="" id="" value="{{ $dvnl->id }}">
                                        {{ $dvnl->tenloai }}</option>
                                    }
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-edit-right">
                        <div class="form-group">
                            <input type="text" hidden value="{{ $spham->hinhanh }}" name="imageOld">
                        </div>
                        <div class="show-img-mal">
                            <img src="{{ asset('uploads/product/' . $spham->hinhanh) }}" alt="{{ $spham->ten_spham }}"
                                id="preview_images" name="preview_images" style="width: 600px;height:300px">
                        </div>
                        <div class="form-group">
                            <input type="file" name="ProductImage" id="ProductImage" onchange="preview_image(this)"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Nội dung</label><br>
                            <textarea name="conten_edit" id="conten_edit" cols="100" rows="20" >{{$spham->noidung}}</textarea>
                            @if ($errors->first('conten_edit'))
                            <div class="btn-danger">
                                {{ $errors->first('conten_edit') }}
                            </div>
                        @endif
                        </div>
                        <div class="form-group">
                            <label for="">Mô tả</label><br>
                            <textarea name="description_edit" id="" cols="100" rows="20" >{{$spham->mota}}</textarea>
                            @if ($errors->first('description_edit'))
                            <div class="btn-danger">
                                {{ $errors->first('description_edit') }}
                            </div>
                        @endif
                        </div>
                        <p>Trạng thái</p>
                        @if ($spham->trangthai == 1)
                            <input type="radio" id="showstatus" name="status_product" value="1" checked>
                            <label for="css">Hiện</label><br>
                            <input type="radio" id="hidestatus" name="status_product" value="0">
                            <label for="html">Ẩn</label><br>
                        @else
                            <input type="radio" id="showstatus" name="status_product" value="1">
                            <label for="html">Hiện</label><br>
                            <input type="radio" id="hidestatus" name="status_product" value="0" checked>
                            <label for="html">Ẩn</label><br>
                        @endif
                        <br>



                    </div>
                    <button type="submit" class="btn btn-success" style="margin-left: 200px">Lưu thay đổi</button>
                </div>

        </form>

    </div>
@endsection
