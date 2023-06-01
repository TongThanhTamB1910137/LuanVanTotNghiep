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
    <form method="post" class="form-submit" action="{{ route('save.policy')}}" enctype="multipart/form-data">
        @csrf
        <div class="col-md-12 mt-4 ">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fa fa-info"></i> Chính sách</h5>
                </div>
                <div class="card-body row">
                    <div class="col-12">
                        <div class="form_group">
                            <label>Tiêu dề</label>
                            <input name="tieude" autocomplete='off' class="form_control" />
                        </div>
                        @if($errors->first('tieude'))
                        <span class="error text-danger">{{ $errors->first('tieude') }}</span>
                        @endif
                    </div>
                    <div class="col-12">
                        <div class="form_group">
                            <label>Nội dung</label>
                            <textarea name="noidung" id="contentEmail">
                                    </textarea>
                        </div>
                        @if($errors->first('noidung'))
                        <span class="error text-danger">{{ $errors->first('noidung') }}</span>
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
    CKEDITOR.config.height = 600;
}
</script>

@stop