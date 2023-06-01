@extends('templates.admins.layout')
@section('content')
<div class="title-show">
    <h3>SẢN PHẨM</h3>
</div>

<div class="show-message-page">
    @if (session('success_add_pro'))
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
    @if (session('success_edit_pro'))
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

    @if (session('success_del_pro'))
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

    {{-- delete session --}}
    {{ Session::forget('success_add_pro') }}
    {{ Session::forget('success_edit_pro') }}
    {{ Session::forget('success_del_pro') }}



</div>

<div class="add-material">
    <a href="{{ route('products.addview') }}" class="btn btn-success">THÊM SẢN PHẨM</a>
    <div class="form-search-mal">
        <form action="{{ route('product.search') }}" method="post">
            @csrf
            <!-- <input type="text" placeholder="Nhập tên sản phẩm.." id="keysearch_product" name="search">
            <button type="submit"><i class="fa fa-search"></i></button> -->
        </form>
    </div>
</div>
<div class="content-show" style="font-weight: bold">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="background-color: #dee2e6">STT</th>
                <th>TÊN SẢN PHẨM</th>
                <th>GIÁ BÁN</th>
                <th>HÌNH ẢNH</th>
                <th>KÍCH CỠ</th>
                <th>TRẠNG THÁI</th>
                <th>MÔ TẢ SẢN PHẨM</th>
                <th>NỘI DUNG</th>
                <th>THAO TÁC</th>
            </tr>
        </thead>
        <tbody style="position: sticky" id="indexpro">
            <?php $i = 1; ?>
            @foreach ($spham as $sp)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $sp->tensp }}</td>
                <td>{{ currency_format($sp->giaban) }}</td>
                <td><img style="widtd:100px;height:150px" src="{{ asset('uploads/product/' . $sp->hinhanh) }}">
                </td>
                <td>
                    @foreach ($sp->size as $value)
                    {{ $value->size_name }}
                    @endforeach
                </td>
                <td>
                    <?php if ($sp->trangthai == 1) {
                        echo "<button id='btnshowstatus' class='btnstatus' value='$sp->id'>Hiện</button>";
                    } else {
                        echo "<button id='btnhidestatus' class='btnstatus' value='$sp->id'>Ẩn</button>";
                    } ?>
                </td>
                <td><span class="line-5">{{ $sp->mota }}</span></td>
                <td id="contenpro"><span class="line-5">{{ $sp->noidung }}</span></td>
                <td>
                    <a href="{{ route('products.del', $sp->id) }}"><i class="material-icons"
                            style="font-size:24px;color:red">delete</i></a>
                    <a href="{{ route('products.editview', $sp->slug) }}"><i class="fa fa-edit"
                            style="font-size:24px;color:green"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div> <span>{{ $spham->links() }}</span>
<style>
.w-5 {
    display: none;
}
</style>
@endsection