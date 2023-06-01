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
    <form method="post" class="form-submit" action="{{ route('save.intro')}}" enctype="multipart/form-data">
        @csrf
        <div class="col-md-12 mt-4 ">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fa fa-info"></i> Giới thiệu</h5>
                </div>
                <div class="card-body row">
                    <div class="col-12">
                        <div class="form_group">
                            <input hidden value="{{isset($intro->noidung) ? $intro->id : ''}}" name="id" />
                            <textarea rows="20" name="intro" id="contentEmail">
                            {{ isset($intro->noidung) ? $intro->noidung : ''}}
                            </textarea>
                        </div>
                        @if($errors->first('intro'))
                        <span class="error text-danger">{{ $errors->first('intro') }}</span>
                        @endif
                    </div>
                    <div class="col-12 mt-2">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                    Lưu lại
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
    CKEDITOR.config.height = 500;
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