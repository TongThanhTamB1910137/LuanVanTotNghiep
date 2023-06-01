@extends('templates.admins.layout')
@section('content')
    <div class="title-add">
        <h3>them don vi</h3>
    </div>
    <div class="content-add">
        <form action="{{ route('material.addhandle') }}" method="post" id="form-add-material"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="">ten don vi</label>
                <input type="text" name="MaterialUnitName" id="MaterialUnitName" class="form-control">
                @if ($errors->first('MaterialName'))
                    <div class="btn-danger">
                        {{ $errors->first('MaterialName') }}
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-success" >Luu</button>
        </form>
    </div>
@endsection
