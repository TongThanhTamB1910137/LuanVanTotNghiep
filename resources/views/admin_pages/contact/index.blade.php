@extends('templates.admins.layout')
@section('content')

@if(session()->has('errorSendMail'))
<script>
window.addEventListener('load', (e) => {
    toastr.error("{{session()->get('errorSendMail')}}");
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
<div class="container-fluid coupon form_ql">
    <form method="post" class="form-submit" action="{{ route('sendmail.all.contact')}}" enctype="multipart/form-data">
        @csrf
        <div class="card_1">
            <h3 class="card-title">Liên hệ</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col"><input type="checkbox" name="checkedbox" /></th>
                        <th>STT</th>
                        <th style="width: 20%;">Tên</th>
                        <th>Email</th>
                        <th>Tiêu đề</th>
                        <th>Ngày tạo</th>
                        <th>Tình trạng</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($contact) && count($contact))
                    @foreach($contact as $key => $value)
                    <tr>
                        <td scope=" col"><input type="checkbox" value="{{ $value->id}}" name="checks[]" /></>
                        <td scope="row">{{$key + 1}}</td>
                        <td><span class='nowrap'>{{$value->ten}}</span></td>
                        <td>{{$value->email}}</td>
                        <td>{{$value->tieude}}</td>
                        <td>
                            <?php
                            echo gmdate('d/m/Y', strtotime($value->created_at))
                            ?>
                        </td>
                        <td>

                            @if($value->trangthai === 1 )
                            <div class=" badge badge-success">Đã liên hệ
                            </div>
                            @else
                            <div class="badge badge-warning">Đang chờ duyệt</div>
                            @endif

                        </td>
                        <td>

                            <a href="{{ route('detail.contact', $value->id)}}" class="btn btn-info mgr-5" id="edit">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </a>

                            <a href="{{ route('delete.contact', $value->id)}}" class="btn btn-danger mgr-5" id="edit">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </td>

                    </tr>

                    @endforeach
                    @endif
                </tbody>
            </table>
            {{ $contact->links() }}
        </div>
        <div class="col-md-12 mt-4 ">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fa fa-info"></i> Gửi email trả lờI</h5>
                </div>
                <div class="card-body row">
                    <div class="col-12">
                        <div class="form_group">
                            <label>Tiêu dề</label>
                            <input name="tieudemail" autocomplete='off' class="form_control" />
                        </div>
                        @if($errors->first('tieudemail'))
                        <span class="error text-danger">{{ $errors->first('tieudemail') }}</span>
                        @endif
                    </div>
                    <div class="col-12">
                        <div class="form_group">
                            <label>Nội dung</label>
                            <textarea name="noidungmail" id="contentEmail">
                                    </textarea>
                        </div>
                        @if($errors->first('noidungmail'))
                        <span class="error text-danger">{{ $errors->first('noidungmail') }}</span>
                        @endif
                    </div>
                    <div class="col-12 mt-2">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                    Gửi mail
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