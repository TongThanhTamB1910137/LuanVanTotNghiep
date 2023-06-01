@extends('templates.admins.layout')
@section('content')
<div class="container-fluid coupon form_ql">
    <div class="card_1">
        <h3 class="card-title">Quản lý Slide</h3>
        <div class="action">
            <a href="{{ route('add.slide')}}" class="btn_add primary">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                Thêm mới
            </a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th style="width: 20%;">Hình ảnh</th>
                    <th>Tên</th>
                    <th style="width: 10%;">Link</th>
                    <th>Vị trí</th>
                    <th>Trạng Thái</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if(isset($slide) && count($slide))
                @foreach($slide as $key => $value)
                <tr>
                    <td scope="row">{{$key + 1}}</td>
                    <td><img style="width:200px;height:150px; border-radius: 4px; object-fit: cover;"
                            src="{{ asset('uploads/slide/' . $value->hinhanh) }}" alt=""></td>
                    <td>{{$value->ten}}</td>
                    <td><span>asdfsadasdfsdafasfsadfdsfsdfsd</span></td>
                    <td>
                        <form class="form-submit form-position" method="post"
                            action="{{route('position.slide', $value->id)}}">
                            @csrf
                            <div class="form_group">
                                <select name="vitri" id="position" class="form_control">
                                    @if($count > 0)
                                    @for($i = 1; $i <= $count ; $i++) <option value="{{$i}}"
                                        {{(+$value->vitri === $i ? 'selected' : '')}}>{{$i}} </option>
                                        @endfor
                                        @endif
                                </select>
                            </div>

                        </form>
                    </td>
                    <td>
                        @if(+$value->trangthai === 1 )
                        <a href="{{route('active.slide', $value->id)}}">
                            <div class=" badge badge-success">
                                Hiển thị
                            </div>
                        </a>
                        @else
                        <a href="{{ route('active.slide', $value->id)}}">
                            <div class="badge badge-danger">
                                Ẩn
                            </div>
                        </a>
                        @endif

                    </td>
                    <td>

                        <a href="{{ route('edit.slide', $value->id)}}" class="btn btn-info mgr-5" id="edit">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>

                        <a href="{{ route('delete.slide', $value->id)}}" class="btn btn-danger mgr-5" id="edit">
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

<script>
window.onload = () => {
    const positions = document.querySelectorAll('#position');
    positions.forEach(position => {
        position.addEventListener('change', (e) => {
            const formPosition = e.target.closest('.form-position');
            formPosition.submit();
        })
    })
}
</script>
@stop