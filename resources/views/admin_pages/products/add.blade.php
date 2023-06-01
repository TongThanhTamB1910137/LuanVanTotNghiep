@extends('templates.admins.layout')
@section('content')
    <div class="title-add">
        <h3>THÊM SẢN PHẨM</h3>
    </div>
    {{ Breadcrumbs::render('Thêm sản phẩm') }}
    <div class="content-add showind">
        <form action="{{ route('products.addhandle') }}" method="post" id="form-add-material" enctype="multipart/form-data">
            @csrf
            <div class="form-add-material-l">
                <div class="form-group">
                    <label for="">Tên sản phẩm</label>
                    <input type="text" name="ProductName" id="ProductName" class="form-control">
                    @if ($errors->first('ProductName'))
                        <div class="btn-danger">
                            {{ $errors->first('ProductName') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="">Giá bán</label>
                    <input type="text" name="SellPrice" id="SellPrice" class="form-control">
                    @if ($errors->first('SellPrice'))
                        <div class="btn-danger">
                            {{ $errors->first('SellPrice') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for=""><strong>Kích thước</strong></label><br>
                    {{-- @foreach ($size as $s) --}}
                    <div class="size-choose">
                        <label for="">Kích thước mặc định là : nhỏ</label><br>
                        <label for="">Bạn có muốn chọn thêm kích cỡ :</label>
                        <input type="checkbox" name="sizePro" id="sizeChoose" value="3">Lớn
                    </div>
                    {{-- @endforeach --}}
                    @if ($errors->first('sizeChoose'))
                        <div class="btn-danger">
                            {{ $errors->first('sizeChoose') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="">Mô tả sản phẩm</label><br>
                    <textarea name="Description" id="Description" style="width:100%" cols="30" rows="10"></textarea>
                </div>
                @if ($errors->first('Description'))
                    <div class="btn-danger">
                        {{ $errors->first('Description') }}
                    </div>
                @endif
                <div class="form-group">
                    <label for="">Nội dung</label><br>
                    <textarea name="contenproduct" id="contenproduct" style="width:100%" cols="30" rows="10"></textarea>
                </div>
                @if ($errors->first('contenproduct'))
                    <div class="btn-danger">
                        {{ $errors->first('contenproduct') }}
                    </div>
                @endif
                @if ($errors->any())
                    <script type="text/javascript">
                        $(document).ready(function() {
                            toastr.error("Kiểm tra lại giá trị nhập vào");
                        });
                    </script>
                @endif
                <div class="form-group">
                    <label for="">Hình ảnh</label>
                    <input type="file" name="ProductImage" id="ProductImage" class="form-control">
                    @if ($errors->first('ProductImage'))
                        <div class="btn-danger">
                            {{ $errors->first('ProductImage') }}
                        </div>
                    @endif
                </div>
                @if (session('error_nameexists'))
                    <div class="show-alert-error">
                        <script type="text/javascript">
                            $(document).ready(function() {
                                Swal.fire({
                                    title: 'Tên sản phẩm đã tồn tại',
                                    icon: 'warning',
                                    timer: 2000
                                });
                            });
                        </script>
                    </div>
                @endif
                <div class="form-group">
                    <label for="">Category</label>

                    <select name="select_cat">
                        @foreach ($categories as $cat)
                            <option name="" id="" value="{{ $cat->id }}">
                                {{ $cat->tenloai }}</option>
                        @endforeach
                    </select>

                    @if ($errors->first('select_cat'))
                        <div class="btn-danger">
                            {{ $errors->first('select_cat') }}
                        </div>
                    @endif
                </div>
                {{ Session::forget('error_nameexists') }}
            </div>
            <button type="submit" class="btn btn-success" id="btn-add-material">Lưu</button>
        </form>
    </div>
@endsection
