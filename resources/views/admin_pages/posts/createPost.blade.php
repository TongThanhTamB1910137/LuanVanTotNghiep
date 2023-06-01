@extends('templates.admins.layout')
@section('content')
<div class="container-fluid coupon form_ql">
    <div class="card_1">
        <h3 class="card-title">Thêm tin tức</h3>
        <div class="action">
            <a href="{{ route('get.post')}}" class="btn_add primary">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
                Quay lại
            </a>
        </div>
        <div class="form-submit">
            <form action="{{ route('save.post')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8 col-12">
                        <div class="form_group">
                            <label>Tiêu đề</label>
                            <input name="tieude" autocomplete='off' class="form_control" />
                        </div>
                        @if($errors->first('tieude'))
                        <span class="error text-danger">{{ $errors->first('tieude') }}</span>
                        @endif
                        <div class="form_group">
                            <label>Danh mục</label>
                            <select name="danhmuc" class="form_control">
                                @if(isset($menuPost) && count($menuPost))
                                @foreach($menuPost as $value)
                                <option value="{{$value->id}}">{{$value->tendanhmuc}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        @if($errors->first('danhmuc'))
                        <span class="error text-danger">{{ $errors->first('danhmuc') }}</span>
                        @endif
                    </div>
                    <div class="col-md-4 col-12">
                        <div class=" img-preview">
                            <label for="img" class="preview">
                                <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                <span>Chọn ảnh cần thêm</soan>
                            </label>
                            @if($errors->first('hinhanh'))
                            <span class="error text-danger">{{ $errors->first('hinhanh') }}</span>
                            @endif
                            <input id="img" type="file" name="hinhanh" hidden class="form_control" />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form_group">
                            <label>Mô tả</label>
                            <textarea class="form_control" name="noidung" id="contentEmail"></textarea>
                        </div>
                        @if($errors->first('noidung'))
                        <span class="error text-danger">{{ $errors->first('noidung') }}</span>
                        @endif
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
<script>
window.onload = () => {
    const img = document.querySelector('#img');
    const preview = document.querySelector('.preview');
    CKEDITOR.config.height = 600;

    img.addEventListener('change', (e) => {
        let file = e.target.files[0];
        if (!file) {
            return;
        }
        let img = document.createElement('img');
        let fileReader = new FileReader();
        fileReader.readAsDataURL(file);
        // img.src = URL.createObjectURL(file);
        fileReader.onloadend = (e) => {
            img.src = e.target.result;
        }
        preview.appendChild(img);
    });




}
</script>


@stop