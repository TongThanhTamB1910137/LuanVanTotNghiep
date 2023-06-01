@extends('templates.admins.layout')
@section('content')
    <div class="title-show">
        <h3>Quản lý nguyên liệu sử dụng</h3>
    </div>
    @if (session('delete_success'))
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
    @if (session('loi_ten_ton_tai'))
        <h4 style="color: red">{{ Session::get('loi_ten_ton_tai') }}</h4>
    @endif
    {{ Session::forget('loi_ten_ton_tai') }}
    @if (session('update_success'))
        <div class="show-alert-succes">
            <script type="text/javascript">
                $(document).ready(function() {
                    Swal.fire({
                        title: 'Cập nhật thành công!',
                        icon: 'success',
                        timer: 2000
                    });
                });
            </script>
        </div>
    @endif

    {{ Session::forget('update_success') }}
    {{ Session::forget('delete_success') }}
    {{ Session::forget('add_success') }}


    <div class="them-nguyen-lieu-dung">
        <button class="btn btn-success"> <a href="{{ route('mmu.addview') }}" style="color:white" id="addModalMMU">Thêm nguyên
                liệu dùng</a> </button>
    </div>
    <br>
    {{-- <div class="sap-xep-nguyen-lieu-sd">
        <form action="{{ route('sort-mmu-by-day') }}" method="post">
            @csrf
            <input type="date" name="dateSorto" id="dateSorto">
            <button type="submit"><a href="{{ route('sort-mmu-by-day') }}">Lọc</a></button>
        </form>
    </div> --}}
    <br>
    <div class="content-show">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên Nguyên Liệu</th>
                    <th>Số Lượng</th>
                    <th>Đơn Giá</th>
                    <th>Ngày Tổng Kết</th>
                    <th>Trạng thái</th>
                    <th>Thành tiền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody id="show-manager-material-use "style="font-size:16px;font-weight:bold">
                <?php $i = 1; ?>
                @foreach ($managerM as $m)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>
                            @foreach ($nameM as $n)
                                @if ($n->id == $m->id_nguyen_lieu)
                                    {{ $n->ten_nglieu }}
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $m->so_luong }}</td>
                        <td>{{ currency_format($m->don_gia) }}</td>
                        <td>{{ $m->ngay_tong_ket }}</td>
                        <td>{{ $m->trang_thai }}</td>
                        <td>{{ currency_format($m->so_luong * $m->don_gia) }}</td>
                        <td>
                            <a href="{{ route('mmu.del', $m->id) }}"><i class="fa fa-trash"
                                    style="width: 16px;height: 16px;color:red"></i></a>
                            <a href="{{ route('mmu.editview', $m->id) }}"><i class="fa fa-edit"
                                    style="width: 16px;height: 16px;color:green"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <?php // dd(session()->all());
    ?>
    <span>{{ $managerM->links() }}</span>
    <style>
        .w-5 {
            display: none;
        }
    </style>
    {{-- add material use --}}
    <div class="modal fade" id="addmmu" tabindex="-1" aria-labelledby="addmmuModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleAddMaterial">Thêm nguyên liệu sử dụng</h5>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('mmu.addhandle') }}" id="form-addmalt">
                        @csrf
                        <div class="form-group">
                            <label for="tennguyenlieu1" class="">Ten nguyen lieu</label>
                            <div class="">
                                <input type="text" class="form-control" id="inputNameMMU" name="inputNameMMU">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="">Số lượng</label>
                            <div class="">
                                <input type="number" class="form-control" name="inputQuantityMMU" id="inputQuantityMMU">
                            </div>
                        </div><button type="submit">luu</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="addmmuhandle">Save changess</button>
                </div>
            </div>
        </div>
    </div>

    {{-- End --}}
@endsection
