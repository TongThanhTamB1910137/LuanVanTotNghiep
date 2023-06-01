<div class="order">
    <div class="orderInfo">
        <ul>
            <li><span>Mã đơn hàng :</span> {{$order->madh}}</li>
            <li><span>Ngày mua :</span> {{ format_date($order->ngaytao)}}</li>
            <li><span>Tổng :</span> {{currency_format($order->tongtien)}}</li>
            <li><span>Trạng thái :</span>
                <div class="badge badge-{{ $order->getStatus($order->trangthai)['class']}} ">
                    {{ $order->getStatus($order->trangthai)['name']}}
                </div>
            </li>
            <li><span> <i class="fa fa-money" aria-hidden="true"></i> Thông tin thanh toán :</span>
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
            <li><span>Tên khách hàng :</span> {{$order->hoten}}</li>
            <li><span>Email :</span> {{$order->email}}</li>
            <li><span>SĐT :</span> {{$order->dienthoai}}</li>
            <li><span>Địa chỉ :</span> {{$order->diachi}}</li>
            <li><span>Ghi chú :</span> {{$order->ghichu}}
            </li>

        </ul>
    </div>
</div>
<table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
    <h4 class="h4 mb-2 text-gray-800 mg-tb">Thông tin sản phẩm</h4>
    <thead>
        <tr>
            <th>Mã ĐH</th>
            <th>Sản phẩm</th>
            <th>Size</th>
            <th>Số lượng</th>
            <th>Giá bán</th>
            <th class="t-right">Tổng</th>
        </tr>
    </thead>
    <tbody>
        @if($orderDetail && count($orderDetail))
        @foreach($orderDetail as $value)
        <tr>
            <td>
                {{ $value->order->madh}}
            </td>
            <td>{{ $value->product->tensp ?? "[]" }}</td>
            <td>{{ $value->size->size_name }}</td>
            <td>
                {{ $value->soluong }}
            </td>
            <td>
                <?php
                $giaban = $value->product->giaban + $value->size->price;
                if ($value->giagoc) {
                    $down = $value->getCoupon->giamgia;
                    if ($value->getCoupon->loaigiam == 1) {
                        $down = $giaban * ($value->getCoupon->giamgia / 100);
                    }
                    $giaban = $giaban - $down;
                }
                ?>
                {{ currency_format(($giaban > 0 ) ? $giaban : 0)}}
            </td>
            <td class="t-right">
                {{currency_format($value->giaban )}}
            </td>
        </tr>
        @endforeach
        @endif
        <tr class="td-right">
            <td colspan="4" class="td-right">
                <b> Tổng tiền sản phẩm :</b>
            </td>
            <td colspan="2" class="td-right">
                <span>
                    {{currency_format($order->tongdonhang)}}</span>
            </td>
        </tr>
        @if($order->Coupon)
        <tr class="td-right">
            <td colspan="4">
                <b>Giảm giá :</b><span>
            </td>
            <td colspan="2" class="td-right">
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
            <td colspan="2" class="td-right">
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
            <td colspan="2" class="td-right">
                <span>
                    {{currency_format($order->tongtien)}}
                </span>
            </td>
        </tr>
    </tbody>
</table>