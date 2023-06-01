@extends('templates.admins.layout')
@section('content')
    <div class="title-add">
        <h3>THEM NGUYEN LIEU</h3>
    </div>
    {{ Breadcrumbs::render('Thêm nguyên liệu') }}


    @if (Session::has('failadd'))
        <div class="alert alert-danger" style="font-size:24px"> {{ Session::get('failadd') }}</div>
    @endif
    @if (session('error_date'))
        <div class="show-alert-succes">
            <script type="text/javascript">
                $(document).ready(function() {
                    toastr.warning("Ngày hết hạn nhỏ hơn ngày hôm nay");
                });
            </script>
        </div>
    @endif

    <div class="content-add input-group-css showind">
        <form action="{{ route('material.addhandle') }}" method="post" id="form-add-material" enctype="multipart/form-data">
            @csrf
            <div class="form-input" style="display: flex;justify-content: space-between">
                <div class="form-add-material-l" style="width: 100%">
                    <div class="form-group">
                        <label for="">Tên nguyên liệu</label>
                        <input type="text" name="MaterialName" id="MaterialName" class="form-control">
                        @if ($errors->first('MaterialName'))
                            <div class="btn-danger">
                                {{ $errors->first('MaterialName') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Giá Nhập</label>
                        <input type="number" name="ImportPrice" id="ImportPrice" class="form-control">

                        @if ($errors->first('ImportPrice'))
                            <div class="btn-danger">
                                {{ $errors->first('ImportPrice') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Số lượng</label>
                        <input type="text" name="MaterialQuantily" id="MaterialQuantily" class="form-control">
                        @if ($errors->first('MaterialQuantily'))
                            <div class="btn-danger">
                                {{ $errors->first('MaterialQuantily') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Hình ảnh</label>
                        <input type="file" name="MaterialImage" id="MaterialImage" class="form-control"
                            onchange="preview_image_add()">
                        @if ($errors->first('MaterialImage'))
                            <div class="btn-danger">
                                {{ $errors->first('MaterialImage') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Đơn vị nguyên liệu</label>
                        {{-- <input type="text" name="MaterialUnit" id="MaterialUnit" class="form-control"> --}}
                        <select name="select_unit">
                            @foreach ($dv_nglieu as $dvnl)
                                <option name="" id="" value="{{ $dvnl->ten_don_vi }}">
                                    {{ $dvnl->ten_don_vi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Hạn sử dụng</label>
                        <input type="date" name="ExpiredDate" id="ExpiredDate" class="form-control">
                        @if ($errors->first('ExpiredDate'))
                            <div class="btn-danger">
                                {{ $errors->first('ExpiredDate') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="show-img-mal">
                    <img src="{{ asset('uploads/materials/noimage.jpg') }}" name="hinh_anh_add" id="hinh_anh_add"
                        style="width: 600px;height:300px">

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
                @if ($errors->any())
                    <script type="text/javascript">
                        $(document).ready(function() {
                            toastr.error("Kiểm tra lại giá trị nhập vào");
                        });
                    </script>
                @endif
                {{-- delete session --}}
                {{ Session::forget('error_nameexists') }}
                {{ Session::forget('error_date') }}

            </div>
            <button type="submit" class="btn btn-success" onclick="checkInputMaterial()" id="btn-add-material">Lưu</button>
        </form>
    </div>
@endsection
