@extends('templates.admins.layout')
@section('content')
    <div class="title-show">
        <h3>Don Vi Nguyen Lieu</h3>
    </div>
    <div class="add-materialunit">
        <a href="{{ route('material.addview') }}">Them Don Vi</a>
        <div class="form-search-mal">
            <form action="{{ route('material.search') }}" method="post">
                @csrf
                <input type="text" placeholder="Search.." name="search" id="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>
    @if (session('success_del_mal'))
        <div class="notify-del-mal" id="notify-del-mal">
            <h4 style="background: green;padding: 10px;text-align:center;width: 500px;color: white;">
                xoa thanh cong</h4>
            <?php Session::flush(); ?>
        </div>
    @endif

    <div class="content-show">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>id</th>
                    <th>ten don vi</th>
                    <th>trang thai</th>
                    <th>thao tac</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($don_vi as $dv)
                    <tr>
                        <td>{{ $dv->id }}</td>
                        <td>{{ $dv->ten_don_vi }}</td>
                        <td>{{ $dv->trang_thai }}</td>
                        <td>
                            <a href="{{ route('material.delete', $nl->id) }}"><i class="material-icons"
                                    style="font-size:24px;color:red">delete</i></a>
                            <a href="{{route('material.addView',$nl->id)}}"><i class="fa fa-edit" style="font-size:24px;color:green"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
