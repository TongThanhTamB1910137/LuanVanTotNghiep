@extends('templates.admins.layout')
@section('content')

<div class="content-order">
    <div class="container-fluid p-0">
        <h1 class="h6 mb-3">Đơn hàng #{{$order->madh}}</h1>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fa fa-shopping-bag"></i> Thông tin đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="order">
                            <div class="orderInfo">
                                <ul>
                                    <li><span>Mã đơn hàng :</span> {{$order->madh}}</li>

                                    <li><span>Trạng thái :</span>
                                        <div class="badge badge-{{ $order->getStatus($order->trangthai)['class']}} ">
                                            {{ $order->getStatus($order->trangthai)['name']}}
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="orderInfo">
                                <ul>
                                    <li><span>Ngày mua :</span> {{ format_date($order->ngaytao)}}</li>
                                    <li><span>Tổng :</span> {{currency_format($order->tongtien)}}</li>
                                </ul>
                            </div>

                        </div>

                        <div class="progress-status">

                            <div class="progress">
                                <ul>
                                    @if($order->trangthai == -1)
                                    <li
                                        class="step step01  whiteT {{ ($order->trangthai >= -1 ) ? 'active cancelT' : ''}}">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                        <div class="step-inner"></div>
                                    </li>
                                    <li class="step step02 {{ ($order->trangthai >= -1 ) ? 'active cancelT' : ''}}">
                                        <i class="fa fa-ban" aria-hidden="true"></i>
                                        <div class="step-inner">Đã huỷ</div>
                                    </li>
                                    @else
                                    <li class="step step01  {{ ($order->trangthai >= 1 ) ? 'active' : ''}}">
                                        <i class="fa fa-spinner" aria-hidden="true"></i>
                                        <div class="step-inner">Chờ xác nhận</div>
                                    </li>
                                    <li class="step step02 {{ ($order->trangthai >= 2 ) ? 'active' : ''}}">
                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                        <div class="step-inner">Đang xử lí</div>
                                    </li>
                                    <li class="step step03 {{ ($order->trangthai >= 3 ) ? 'active' : ''}}">
                                        <i class="fa fa-truck" aria-hidden="true"></i>
                                        <div class="step-inner">Đang vận chuyển</div>
                                    </li>
                                    <li class="step step04 {{ ($order->trangthai >= 4 ) ? 'active' : ''}}">
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                        <div class="step-inner">Đã giao</div>
                                    </li>
                                    @endif
                                </ul>

                                <div class="line {{ $order->trangthai == -1  ? 'cancel' : ''}}">
                                    <div class="line-progress {{ $order->getStatus($order->trangthai)['progress']}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fa fa-user"></i> Thông tin khách hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="order">
                            <div class="orderCus">
                                <ul>
                                    <li><span>Tên khách hàng :</span> {{$order->hoten}}</li>
                                    <li><span>Email :</span> {{$order->email}}</li>
                                    <li><span> <i class="fa fa-money" aria-hidden="true"></i> Thông tin thanh toán
                                            :</span>
                                        @if($order->trangthaithanhtoan == 0 )
                                        <div class="badge badge-warning">Chờ thanh toán</div>
                                        @else
                                        <div class="badge badge-success">Đã thanh toán</div>
                                        @endif

                                    </li>
                                </ul>
                            </div>
                            <div class="orderCus">
                                <ul>
                                    <li><span>SĐT :</span> {{$order->dienthoai}}</li>
                                    <li><span>Địa chỉ :</span> {{$order->diachi}}</li>
                                    <li><span>Ghi chú :</span> {{$order->ghichu}}</li>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fa fa-tasks"></i> Xử lí đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-style mb-3">
                            <label for="StatusOrder">Trạng thái đơn hàng</label>
                            <select name="" id="StatusOrder" class="form-control"
                                data-statusOld="{{$order->trangthai}}">
                                <option value="1" {{  $order->trangthai == 1 ? "selected" : ""}}
                                    data-href="{{ route('get.action', ['receive', $order->id])}}">Tiếp nhận</option>
                                <option value="2" {{  $order->trangthai == 2 ? "selected" : ""}}
                                    data-href="{{ route('get.action', ['process', $order->id])}}">Đang xử lí</option>
                                <option value="3" {{  $order->trangthai == 3 ? "selected" : ""}}
                                    data-href="{{ route('get.action', ['transport', $order->id])}}">Đang vận chuyển
                                </option>
                                <option value="4" {{  $order->trangthai == 4 ? "selected" : ""}}
                                    data-href="{{ route('get.action', ['success', $order->id])}}">Đã giao</option>
                                <option value="-1" {{  $order->trangthai == -1 ? "selected" : ""}}
                                    data-href="{{ route('get.action', ['cancel', $order->id])}}">Đã huỷ</option>
                            </select>
                        </div>
                        <div class="form-style mb-3">
                            <label for="PaymentStatus">Trạng thái thanh toán</label>
                            <select name="" id="PaymentStatus" class="form-control"
                                data-statusoldPay="{{$order->trangthaithanhtoan}}">
                                <option value="1" {{  $order->trangthaithanhtoan == 1 ? "selected" : ""}}
                                    data-href="{{ route('get.actionPayment', ['success', $order->id])}}">Đã thanh toán
                                </option>
                                <option value="2" {{  $order->trangthaithanhtoan == 0 ? "selected" : ""}}
                                    data-href="{{ route('get.actionPayment', ['process', $order->id])}}">Chờ thanh toán
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fa fa-info"></i>
                            Thông tin sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Size</th>
                                    <th>Số lượng</th>
                                    <th>Giá gốc</th>
                                    <th class="td-right">Giá bán</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($orderDetail && count($orderDetail))
                                @foreach($orderDetail as $value)
                                <tr>
                                    <td>
                                        <img src="{{ asset('uploads/product/'.$value->product->hinhanh)}}"
                                            class="img-fluid" alt="" /> {{ $value->product->tensp ?? "[]" }}
                                    </td>
                                    <td>{{ $value->size->size_name }}</td>
                                    <td>
                                        {{ $value->soluong }}
                                    </td>
                                    <td>
                                        @if($value->giagoc)
                                        {{currency_format($value->product->giaban + $value->size->price)}}
                                        <br />
                                        {{'Khuyến mãi : ( - '.currency_format($value->getCoupon->giamgia, ($value->getCoupon->loaigiam === 2) ? 'đ' : '%').' )'}}
                                        @else
                                        {{currency_format($value->giaban)}}
                                        @endif
                                    </td>
                                    <td class="td-right">
                                        {{currency_format($value->giaban)}}
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                                <tr class="td-right">
                                    <td colspan="4" class="td-right">
                                        <b> Tổng tiền sản phẩm :</b>
                                    </td>
                                    <td class="td-right">
                                        <span>
                                            {{currency_format($order->tongdonhang)}}</span>
                                    </td>
                                </tr>
                                @if($order->Coupon)
                                <tr class="td-right">
                                    <td colspan="4">
                                        <b>Giảm giá :</b><span>
                                    </td>
                                    <td class="td-right">
                                        <span class="no-wrap">
                                            @if($order->Coupon->loaigiam === 1)
                                            <span> {{ $order->Coupon->giamgia}}%
                                                ( -
                                                {{currency_format($order->tongdonhang *  $order->Coupon->giamgia / 100)}}
                                                )</span>
                                            @else
                                            <span> -
                                                {{currency_format($order->Coupon->giamgia)}}</span>
                                            @endif

                                        </span>
                                    </td>
                                </tr>
                                @endif
                                <tr class="td-right">
                                    <td colspan="4">
                                        <b>Tiền phí vận chuyển : </b>
                                    </td>
                                    <td class="td-right">
                                        <span>
                                            @if($order->id_feeship && $order->Ship->feeship)
                                            + {{currency_format($order->Ship->feeship)}}
                                            @else
                                            {{currency_format(0)}}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <tr class="td-right">
                                    <td colspan="4">
                                        <b>Thành tiền :</b>
                                    </td>
                                    <td class="td-right">
                                        <span>
                                            {{currency_format($order->tongtien)}}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        {{ $orderDetail->links() }}



                    </div>
                </div>
            </div>
        </div>

        @if(isset($payment))
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fa fa-info"></i> Thông tin thanh toán</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Số tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Mã giao dịch</th>
                                    <th>Số hoá đơn</th>
                                    <th>Loại thanh toán</th>
                                    <th>Mã đơn hàng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php date_default_timezone_set('Asia/Ho_Chi_Minh'); ?>
                                        @if($payment->Donhang->httt === 3)
                                        <?= date("d-m-Y H:i:s", strtotime($payment->ngaythanhtoan)) ?>
                                        @elseif ($payment->Donhang->httt === 2)
                                        <?= date("d-m-Y H:i:s ", substr($payment->ngaythanhtoan, 0, 10)) ?>
                                        @else
                                        <!-- {{$payment->ngaythanhtoan}} -->
                                        <?= date("d-m-Y H:i:s", strtotime($payment->ngaythanhtoan)) ?>
                                        @endif
                                    </td>
                                    <td>@if(+$order->httt === 1)
                                        {{currency_format($payment->tongtien * 23187)}}
                                        @else
                                        {{currency_format($payment->tongtien)}}
                                        @endif
                                    </td>
                                    <td>

                                        @if($payment->trangthai == 1 )
                                        <div class="badge badge-success">Thành công</div>
                                        @else
                                        <div class="badge badge-danger">Thất bại</div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $payment->magiaodich}}
                                    </td>
                                    <td>
                                        {{ $payment->sohoadon}}
                                    </td>
                                    <td>
                                        {{ $payment->loaithanhtoan}}
                                    </td>
                                    <td>
                                        {{ $payment->Donhang->madh}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

</div>
</div>

<script>
$('#StatusOrder').on('change', function() {
    let array = ['Chờ xác nhận', 'Đang xử lí', 'Đang vận chuyển', 'Đã giao', 'Đã huỷ'];
    let status = ($(this).val() == -1) ? 5 : $(this).val()
    let statusOld = ($(this).data('statusold') == -1) ? 5 : $(this).data('statusold')
    let href = $(this).find(':selected').data('href')
    $.confirm({
        type: 'blue',
        title: 'TRẠNG THÁI ĐƠN HÀNG',
        content: `Vui lòng xác nhận đơn hàng chuyển từ <span class="text-danger"> ${array[statusOld - 1]} </span> sang <span class="text-danger"> ${array[status - 1]} </span>.`,
        buttons: {
            'Xác nhận': {
                text: 'Xác nhận',
                keys: ['enter'],
                btnClass: 'btn-blue',
                action: function() {
                    window.location.href = href
                },
            },
            Huỷ: function() {

            }

        }
    });

});


$('#PaymentStatus').on('change', function() {
    let array = ['Chờ thanh toán', 'Đã thanh toán'];
    let statusOld = $(this).data('statusoldpay')
    let status = $(this).val()
    let href = $(this).find(':selected').data('href')

    $.confirm({
        type: 'blue',
        animation: 'zoom',
        closeAnimation: 'scaleX',
        title: 'TRẠNG THÁI THANH TOÁN',
        content: `Vui lòng xác nhận đơn hàng chuyển từ <span class="text-danger"> ${array[statusOld - 1]} </span> sang <span class="text-danger"> ${array[status - 1]} </span>.`,
        buttons: {
            'Xác nhận': {
                text: 'Xác nhận',
                keys: ['enter'],
                btnClass: 'btn-blue',
                action: function() {
                    window.location.href = href
                },
            },
            Huỷ: function() {

            }
        }
    });

});
</script>
@stop