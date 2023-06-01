@extends('templates.admins.layout')
@section('content')
<div class="title-show">
    <h3>Phân quyền</h3>
</div>

@if (session('status_delstaff'))
<div class="show-alert-succes">
    <script type="text/javascript">
    $(document).ready(function() {
        Swal.fire({
            title: 'Xoá thành công!',
            icon: 'success',
            timer: 2000
        });
    });
    </script>
</div>
@endif
@if (session('update_success'))
<div class="show-alert-succes">
    <script type="text/javascript">
    $(document).ready(function() {
        Swal.fire({
            title: 'Thay đổi thành công!',
            icon: 'success',
            timer: 2000
        });
    });
    </script>
</div>
@endif

@if (session('add_staff_success'))
<div class="show-alert-succes">
    <script type="text/javascript">
    $(document).ready(function() {
        Swal.fire({
            title: 'Thêm thành công!',
            icon: 'success',
            timer: 2000
        });
    });
    </script>
</div>
@endif
{{ Session::forget('add_staff_success') }}
{{ Session::forget('status_delstaff') }}
{{ Session::forget('update_success') }}

<div class="addnew">
    <a href="{{ route('roles.addview') }}"><button class="btn btn-primary">Thêm tài khoản mới</button></a>
</div>
<div class="content-show" style="font-weight: bold">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Mail</th>
                <th>Tên</th>
                <th>SDT</th>
                {{-- <th>Quyền</th> --}}
                <th>Loại tài khoản</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <?php $i = 1; ?>
        <tbody id="show-manager-material-use showind">
            @foreach ($getStaff as $item)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->name_staff }}</td>
                <td>{{ $item->phone_number }}</td>
                <td>
                    @if ($item->type_account == 1)
                    admin
                    @endif
                    @if ($item->type_account == 2)
                    nhân bán hàng
                    @endif
                </td>
                <td>{{ $item->status ? 'hoat dong' : 'ngung hoat dong' }}</td>
                <td>

                    <a href="{{ route('del_staff', $item->id) }}"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="red" class="bi bi-trash3" viewBox="0 0 16 16">
                            <path
                                d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z" />
                        </svg></a>
                    <a href="{{route('edithandle',$item->id)}}"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="green" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                            <path fill-rule="evenodd"
                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                        </svg></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection