@extends('templates.admins.layout')
@section('content')
@if(session()->has('message'))
<script>
window.addEventListener('load', (e) => {
    toastr.warning("{{session()->get('message')}}");
})
</script>
@endif
<div class="container-fluid coupon form_ql">
    <form method="post" class="form-submit" action="" enctype="multipart/form-data">
        @csrf
        <div class="card_1">
            <h3 class="card-title">Tin tức</h3>
            <a href=" {{ route('create.post')}}" class="btn btn-primary">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                Thêm mới
            </a>
            <table class="table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Hình ảnh</th>
                        <th style="width: 40%;">Tiêu đề</th>
                        <th>Danh mục</th>
                        <th>Hot</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($post) && count($post))
                    @foreach($post as $key => $value)
                    <tr>
                        <td scope="row">{{$key + 1}}</td>
                        <td>
                            @if($value->hinhanh)
                            <img style="width: 100px; height: 100px; border-radius: 4px; object-fit: cover;"
                                src="{{ asset('uploads/post/'.$value->hinhanh) }}" alt="{{$value->tieude}}" />
                            @else
                            <img style="width: 100px; height: 100px; border-radius: 4px; object-fit: cover;"
                                src="{{ asset('img/no-img.png') }}" alt="no-img" />
                            @endif
                        </td>
                        <td>
                            {{$value->tieude}}
                        </td>
                        <td>
                            <div class=" badge badge-info">
                                {{$value->danhmuc->tendanhmuc}}
                            </div>
                        </td>
                        <td>

                            @if(+$value->hot === 1 )
                            <a href="{{route('hot.post', $value->id)}}">
                                <div class=" badge badge-success">
                                    Có
                                </div>
                            </a>
                            @else
                            <a href="{{ route('hot.post', $value->id)}}">
                                <div class="badge badge-danger">
                                    Không
                                </div>
                            </a>
                            @endif

                        </td>
                        <td>
                            @if(+$value->trangthai === 1 )
                            <a href="{{route('active.post', $value->id)}}">
                                <div class=" badge badge-success">
                                    Hiển thị
                                </div>
                            </a>
                            @else
                            <a href="{{ route('active.post', $value->id)}}">
                                <div class="badge badge-danger">
                                    Ẩn
                                </div>
                            </a>
                            @endif
                        </td>
                        <td>

                            <a href="{{ route('edit.post', $value->id)}}" class="btn btn-info mgr-5" id="edit">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </a>

                            <a href="{{ route('delete.post', $value->id)}}" class="btn btn-danger mgr-5" id="edit">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
            {{ $post->links() }}
        </div>
    </form>
</div>
@stop