@extends('templates.admins.layout')
@section('content')

@if(session()->has('successStatus'))
<script>
window.addEventListener('load', (e) => {
    toastr.info("{{session()->get('successStatus')}}");
})
</script>
@endif
@if(session()->has('successSendMail'))
<script>
window.addEventListener('load', (e) => {
    toastr.success("{{session()->get('successSendMail')}}");
})
</script>
@endif
<div class="container-fluid coupon form_ql form-submit">
    <form method="post" class="form-submit" action="{{ route('sendmail.coupon')}}" enctype="multipart/form-data">
        @csrf
        <div class="card_1">
            <h3 class="card-title">Danh sách khách hàng</h3>
            <div class="action">
                <a href="{{ route('get.add.customer')}}" class="btn_add primary">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    Thêm mới
                </a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" name="checkedbox" /></th>
                        <th>STT</th>
                        <th style="width: 20%;">Tên</th>
                        <th>Email</th>
                        <th>Điện thoại</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($customer) && count($customer))
                    @foreach($customer as $key => $value)
                    <tr>
                        <td scope=" col"><input type="checkbox" value="{{ $value->id}}" name="checks[]" /></>
                        <td scope="row">{{$key + 1}}</td>
                        <td><span class='nowrap'>{{$value->ten}}</span></td>
                        <td>{{$value->email}}</td>
                        <td>{{$value->sodienthoai}}</td>
                        <td>

                            @if(+$value->trangthai === 1 )
                            <a href="{{ route('update.status.customer', $value->id)}}">
                                <div class=" badge badge-success">Hoạt động
                                </div>
                            </a>
                            @else
                            <a href="{{ route('update.status.customer', $value->id)}}">
                                <div class="badge badge-danger">Đang khoá</div>
                            </a>
                            @endif

                        </td>
                        <td>

                            <a href="{{ route('get.edit.customer', $value->id)}}" class="btn btn-info mgr-5" id="edit">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </a>

                            <a href="{{ route('delete.customer', $value->id)}}" class="btn btn-danger mgr-5" id="edit">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </td>

                    </tr>

                    @endforeach
                    @endif
                </tbody>
            </table>
            @if($errors->first('checks'))
            <span class="error text-danger">{{ $errors->first('checks') }}</span>
            @endif

        </div>
        <div class="col-md-12 mt-4 ">
            <div class="card">
                <div class=" card-header">
                    <h5 class="card-title mb-0"><i class="fa fa-info"></i> Gửi email khuyến mãi cho khách thành viên
                    </h5>
                </div>
                <div class="card-body row">
                    <div class="col-12">
                        @if(isset($coupon) && count($coupon) > 0)
                        @foreach($coupon as $value)
                        <div class="mt-2">
                            <input id="{{$value->id}}" type="checkbox" name="coupon[]" value="{{$value->id}}" />
                            <label for="{{$value->id}}"
                                style="cursor: pointer; user-select: none;">{{$value->ten}}</label>
                        </div>
                        @endforeach
                        @endif
                        @if($errors->first('coupon'))
                        <span class="error text-danger">{{ $errors->first('coupon') }}</span>
                        @endif
                    </div>
                    <div class="col-12 mt-2">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                    Gửi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>


<script>
window.onload = () => {

    const checkbox = document.querySelector('input[name="checkedbox"]');
    const allCheckBox = document.querySelectorAll('input[name="checks[]"]');
    checkbox.addEventListener('change', (e) => {
        let isCheck = e.target.checked;
        allCheckBox.forEach(item => {
            item.checked = isCheck;
        })
    })

    function getCount() {
        let count = Array.from(allCheckBox).reduce((initial, item) => {
            return item.checked ? initial + 1 : initial;
        }, 0);

        return count;
    }

    allCheckBox.forEach(item => {
        item.addEventListener('change', (e) => {
            checkbox.checked = allCheckBox.length === getCount();
        })
    })
}
</script>

@stop