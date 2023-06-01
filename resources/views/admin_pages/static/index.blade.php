@extends('templates.admins.layout')
@section('content')
<div class="content-order">
    <div class="container-fluid p-0">
        <h1 class="h6 mb-3">Thiết lập thông tin Website</h1>
        <form action="{{ route('post.static')}}" method="post" class="form-submit submit-create-order form_ql">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fa fa-info"></i>Thông tin chung</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="form_group">
                                    <label>Tên Website</label>
                                    <input name="ten" value="{{$setting->name}}" autocomplete='off'
                                        class="form_control" />
                                </div>
                                @if($errors->first('ten'))
                                <span class="error text-danger">{{ $errors->first('ten') }}</span>
                                @endif
                            </div>
                            <div class="col-6">
                                <div class="form_group">
                                    <label>Địa chỉ</label>
                                    <input name="diachi" value="{{$setting->diachi}}" autocomplete='off'
                                        class="form_control" />
                                </div>
                                @if($errors->first('diachi'))
                                <span class="error text-danger">{{ $errors->first('diachi') }}</span>
                                @endif
                            </div>
                            <div class="col-6">
                                <div class="form_group">
                                    <label>Email</label>
                                    <input name="email" value="{{$setting->email}}" type="email" autocomplete='off'
                                        class="form_control" />
                                </div>
                                @if($errors->first('email'))
                                <span class="error text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="col-6">
                                <div class="form_group">
                                    <label>Điện thoại</label>
                                    <input name="dienthoai" value="{{$setting->dienthoai}}" ype="number"
                                        autocomplete='off' class="form_control" />
                                </div>
                                @if($errors->first('dienthoai'))
                                <span class="error text-danger">{{ $errors->first('dienthoai') }}</span>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="form_group">
                                    <label>Tạo độ map Iframe (<a href="https://www.google.com/maps" target="_blank">Lấy
                                            link nhúng</a>)</label>
                                    <textarea name="iframemap" class="form_control" rows="5">{{$setting->iframemap}}
                                    </textarea>
                                </div>
                                @if($errors->first('iframemap'))
                                <span class="error text-danger">{{ $errors->first('iframemap') }}</span>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="map">
                                    @if($setting->iframemap)
                                    {!! html_entity_decode($setting->iframemap) !!}
                                    @endif
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
        </form>

    </div>

</div>
</div>


@stop