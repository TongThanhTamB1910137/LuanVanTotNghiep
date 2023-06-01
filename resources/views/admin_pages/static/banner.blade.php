@extends('templates.admins.layout')
@section('content')
<div class="content-order">
    <form action="{{ route('post.banner')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid p-0">
            <h1 class="h6 mb-3">Thiết lập Banner</h1>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fa fa-picture-o" aria-hidden="true"></i> Banner trang
                                chủ
                            </h5>
                        </div>
                        <div class="card-body row">
                            <div class=" img-preview">
                                @if(isset($bannerHome->hinhanh))
                                <div class="action-banner">
                                    <span><input id="checkhome" class="checkshow"
                                            data-href='{{ route("show.banner", $bannerHome->id)}}' type="checkbox" {{$bannerHome->trangthai === 1 ? 'checked'
                                        : ''}} /><label for="checkhome">Hiển thị</label></span>
                                    <a href="{{route('del.banner', $bannerHome->id)}}" class="btn btn-danger mgr-5"
                                        id="edit">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </div>

                                @endif
                                <label for="bannerHome" class="preview">
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    @if(isset($bannerHome->hinhanh) )
                                    <image src="{{ asset('uploads/slide/' . $bannerHome->hinhanh) }}">
                                        @endif
                                        <span>Chọn ảnh cần thêm</soan>
                                </label>
                                <input id="bannerHome" type="file" name="bannerHome" hidden
                                    class="form_control banner" />
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fa fa-picture-o" aria-hidden="true"></i> Banner sản
                                phẩm
                            </h5>
                        </div>
                        <div class="card-body row">

                            <div class=" img-preview">
                                @if(isset($bannerProduct->hinhanh))
                                <div class="action-banner">
                                    <span><input id="checkpro" class="checkshow"
                                            data-href='{{ route("show.banner", $bannerProduct->id)}}' type="checkbox" {{$bannerProduct->trangthai === 1 ? 'checked'
                                        : ''}} /><label for="checkpro">Hiển thị</label></span>
                                    <a href="{{route('del.banner', $bannerProduct->id)}}" class="btn btn-danger mgr-5"
                                        id="edit">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </div>

                                @endif
                                <label for="bannerProduct" class="preview">
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    @if(isset($bannerProduct->hinhanh))
                                    <image src="{{ asset('uploads/slide/' . $bannerProduct->hinhanh) }}">
                                        @endif
                                        <span>Chọn ảnh cần thêm</soan>
                                </label>
                                <input id="bannerProduct" type="file" name="bannerProduct" hidden
                                    class="form_control banner" />
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        Lưu thông tin
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>
</div>
<script>
window.onload = () => {
    const img = document.querySelectorAll('.banner');
    const preview = document.querySelectorAll('.preview');
    const show = document.querySelectorAll('.checkshow');

    Array.from(show).forEach(item => {
        item.addEventListener('change', e => {
            location.href = e.target.dataset.href;
        })
    })

    Array.from(img).forEach(item => {
        let prevSibling = item.previousElementSibling;
        item.addEventListener('change', (e) => {
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
            prevSibling.appendChild(img);
        });
    })

}
</script>

@stop