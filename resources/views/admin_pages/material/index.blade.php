@extends('templates.admins.layout')
@section('content')
<div class="show-message-page">
    @if (session('success_add_mal'))
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
    @if (session('success_del_mal'))
    <div class="show-alert-del-succes">
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
    @if (session('success_edit_mal'))
    <div class="show-alert-del-succes">
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
    <input id="result_edit" value="thanh cong" hidden>
    @endif


</div>

<div class="title-show">
    <h3>NGUYÊN LIỆU</h3>
</div>
<div class="add-material">
    <a href="{{ route('material.addview') }}" class="btn btn-primary">THÊM NGUYÊN LIỆU</a>
    <div class="form-search-mal">
        {{-- <form action="{{ route('material.search') }}" method="post">
        @csrf
        <input type="text" placeholder="Search.." name="search" id="search">
        <button type="submit"><i class="fa fa-search"></i></button>
        </form> --}}
    </div>
</div>
<div class="content-show">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>TÊN NGUYÊN LIỆU</th>
                <th>GIÁ NHẬP</th>
                <th>HÌNH ẢNH</th>
                <th>SỐ LƯỢNG</th>
                <th>ĐƠN VỊ</th>
                <th>NGÀY NHẬP</th>
                <th>HẠN SỬ DỤNG</th>
                <th>TRẠNG THÁI</th>
                <th>THAO TÁC</th>
            </tr>
        </thead>
        <tbody class="showind">
            <?php $i = 1; ?>
            @foreach ($nglieu as $item)
            <tr style="font-weight: bold">
                <td><?php echo $i;
                    $i++; ?></td>
                <td>{{ $item->ten_nglieu }}</td>
                <td>{{ currency_format($item->gia_nhap) }}</td>
                <td> <img style="width:100px;height:150px" src="{{ asset('uploads/materials/' . $item->hinh_anh) }}">
                </td>

                <td>
                    <?php
                    if ($item->so_luong == 0) {
                        echo '<h4 style="background-color:red;color:white">' . $item->so_luong . '</h4>';
                    }
                    if ($item->so_luong > 1 && $item->so_luong < 50) {
                        echo '<h4 style="background-color:orange;color:white">' . $item->so_luong . '</h4>';
                    }
                    if ($item->so_luong > 50) {
                        echo '<h4 style="background-color:green;color:white">' . $item->so_luong . '</h4>';
                    }
                    ?>
                </td>
                <td>{{ $item->don_vi_nglieu }}</td>
                <td>{{ date('d/m/Y', $item->ngay_nhap) }}</td>
                <td>{{ date('d/m/Y', $item->ngay_het_han) }}</td>
                <td>{{ $item->trang_thai }}</td>
                <td>
                    <a href="{{ route('material.delete', $item->id) }}"><i class="fa fa-trash"
                            style="width: 16px;height: 16px;color:red"></i></a>
                    <a href="{{ route('material.editview', $item->slug) }}"><i class="fa fa-edit"
                            style="width: 16px;height: 16px;color:green"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
    <span>{{ $nglieu->links() }}</span>
    <style>
    .w-5 {
        display: none;
    }
    </style>
</div>
{{ Session::forget('success_add_mal') }}
{{ Session::forget('success_edit_mal') }}
{{ Session::forget('success_del_mal') }}


<!-- Modal Add new materials-->
<div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleAddMaterial">Thêm nguyên liệu mới</h5>
            </div>
            <div class="modal-body">
                <form method="POST" action="#" id="form-addmalt">
                    <div class="form-group">
                        <label for="tennguyenlieu1" class="">Ten nguyen lieu</label>
                        <div class="">
                            <input type="text" class="form-control" id="inputNameMal"
                                placeholder="Nhap ten nguyen lieu">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tennguyenlieu1" class="">Don vi nguyen lieu</label>
                        <div class="">
                            <select name="" id="selectUnit"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="">So luong</label>
                        <div class="">
                            <input type="text" class="form-control" id="soluong" placeholder="So luong">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="">Giá nhap</label>
                        <div class="">
                            <input type="text" class="form-control" id="gia_nhap" placeholder="So luong">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="">Ngay het han</label>
                        <div class="">
                            <input type="date" class="form-control" id="ngay_het_han" placeholder="Ngay het han">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-add-mal">Save changes</button>
            </div>
        </div>
    </div>
</div>


{{-- Delete Modal --}}
<div class="modal fade" id="DeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Xoá Nguyên liệu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4>Bạn có thực sự muốn xoá</h4>
                <input type="hidden" id="deleteing_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary delete_student">Đồng ý</a> </button>
            </div>
        </div>
    </div>
</div>
{{-- End - Delete Modal --}}


{{-- Edit material --}}
<div class="modal fade" id="editMaterialModal" tabindex="-1" aria-labelledby="editMaterialModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleAddMaterial">Sửa nguyên liệu mới</h5>
            </div>
            <div class="modal-body">
                <form method="POST" action="#" id="form-addmalt">
                    <div class="form-group">
                        <label for="tennguyenlieu1" class="">Ten nguyen lieu</label>
                        <div class="">
                            <input type="text" class="form-control" id="inputNameMal">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="">Don vi nguyen lieu</label>
                        <div class="">
                            <select name="" id="selectUnit"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="">So luong</label>
                        <div class="">
                            <input type="text" class="form-control" id="soluong">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="">Giá nhap</label>
                        <div class="">
                            <input type="text" class="form-control" id="gia_nhap">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="">Ngay het han</label>
                        <div class="">
                            <input type="date" class="form-control" id="ngay_het_han">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-add-mal">Save changes</button>
            </div>
        </div>
    </div>
</div>

{{-- End Material --}}
@endsection