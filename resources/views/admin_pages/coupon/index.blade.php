@extends('templates.admins.layout')
@section('content')
@if(session()->has('messageupdate'))
<script>
window.addEventListener('load', (e) => {
    toastr.warning("{{session()->get('messageupdate')}}");
})
</script>
@endif
<div class="container-fluid coupon form_ql">
    <div class="card_1">
        <h3 class="card-title">Danh mục mã khuyến mãi</h3>
        <div class="action">
            <a href="{{ route('add.coupon')}}" class="btn_add primary">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                Tạo mới
            </a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col"><input type="checkbox" /></th>
                    <th>STT</th>
                    <th style="width: 20%;">Tên</th>
                    <th>Code</th>
                    <th>Giảm</th>
                    <th>Kết thúc</th>
                    <th>Dành cho</th>
                    <th>Trạng Thái</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if(isset($coupon) && count($coupon))
                @foreach($coupon as $key => $value)
                <tr>
                    <td scope=" col"><input type="checkbox" /></>
                    <td scope="row">{{$key + 1}}</td>
                    <td><span class='nowrap'>{{$value->ten}}</span></td>
                    <td>{{$value->code}}</td>
                    <td>{{currency_format($value->giamgia, ($value->loaigiam === 2) ? 'đ' : '%')}}</td>
                    <td>
                        <?php
                        echo date('d/m/Y', strtotime($value->ngaykt))
                        ?>
                    </td>
                    <td>
                        @if(+$value->hienthi === 1 )
                        <a href="{{route('show.coupon', $value->id)}}">
                            <div class=" badge badge-info">
                                Hiển thị
                            </div>
                        </a>
                        @else
                        <a href="{{ route('show.coupon', $value->id)}}">
                            <div class="badge badge-warning">
                                Không
                            </div>
                        </a>
                        @endif

                    </td>
                    <td>
                        @if(+$value->trangthai === 1 )
                        <a href="{{route('active.coupon', $value->id)}}">
                            <div class=" badge badge-success">
                                Hoạt động
                            </div>
                        </a>
                        @else
                        <a href="{{ route('active.coupon', $value->id)}}">
                            <div class="badge badge-danger">
                                Ngừng
                            </div>
                        </a>
                        @endif

                    </td>
                    <td>
                        <a href="{{ route('get.detail.coupon', $value->id)}}" class="btn btn-primary mgr-5">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </a>

                        <a href="{{ route('get.edit', $value->id)}}" class="btn btn-info mgr-5" id="edit">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>

                        <a href="{{ route('delete.coupon', $value->id)}}" class="btn btn-danger mgr-5" id="edit">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>
                    </td>

                </tr>

                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>


@stop