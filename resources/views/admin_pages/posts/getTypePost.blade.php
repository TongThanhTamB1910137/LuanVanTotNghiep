@extends('templates.admins.layout')
@section('content')
@if(session()->has('message'))
<script>
window.addEventListener('load', (e) => {
    toastr.info("{{session()->get('message')}}");
})
</script>
@endif
<div class="container-fluid coupon form_ql">
    <form method="post" class="form-submit" action="" enctype="multipart/form-data">
        @csrf
        <div class="card_1">
            <h3 class="card-title">Loại bài viết</h3>
            <a href=" {{ route('create.menupost')}}" class="btn btn-primary">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                Thêm mới
            </a>
            <table class="table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th style="width: 20%;">Loại bài viết</th>
                        <th>Mô tả</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($menupost) && count($menupost))
                    @foreach($menupost as $key => $value)
                    <tr>
                        <td scope="row">{{$key + 1}}</td>
                        <td>{{$value->tendanhmuc}}</td>
                        <td>
                            {{$value->mota}}
                        </td>
                        <td>

                            @if(+$value->trangthai === 1 )
                            <a href="{{route('active.menupost', $value->id)}}">
                                <div class=" badge badge-success">
                                    Hiển thị
                                </div>
                            </a>
                            @else
                            <a href="{{ route('active.menupost', $value->id)}}">
                                <div class="badge badge-danger">
                                    Ẩn
                                </div>
                            </a>
                            @endif

                        </td>
                        <td>

                            <a href="{{ route('edit.menupost', $value->id)}}" class="btn btn-info mgr-5" id="edit">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </a>

                            <a href="{{ route('delete.menupost', $value->id)}}" class="btn btn-danger mgr-5" id="edit">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </td>

                    </tr>

                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </form>
</div>
@stop