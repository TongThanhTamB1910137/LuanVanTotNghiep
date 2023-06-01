<!DOCTYPE html>
<!DOCTYPE html>
<html lang="vn">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng #{{$order->madh}}</title>
    <style>
    * {
        font-family: DejaVu Sans, sans-serif;
    }

    .content {
        text-align: center;
        width: 100%;
        margin: 0 auto;
        margin-top: 50px;
    }

    .header span {
        display: block;
        margin-top: 10px;
    }

    .content .header h1 {
        margin-bottom: 10px;
    }

    .content .header h2 {
        margin-top: 10px;
    }

    .td-right {
        text-align: right;
    }

    .w-50 {
        float: left;
        width: 50%;
    }

    .detail {
        clear: both;
    }

    .info ul {
        list-style: none;
        text-align: left;
        padding: 0px;
    }

    .info ul li {
        margin: 10px 0px;
        font-weight: bold;
    }

    .info ul li span {
        font-weight: 500;
    }

    table tr th {
        text-align: left;
        padding: 10px 0px;
    }

    table tr td {
        padding: 10px 0px;
    }

    .footer {
        margin-top: 50px;
    }
    </style>
</head>

<body>
    <div class="content">
        <div class="header">
            <h1>{{ $setting->name ?? "Drinks Order"}}</h1>
            <h2>HOÁ ĐƠN THANH TOÁN</h2>
            <span>Địa chỉ :{{ $setting->diachi ?? "Thành Phố Hồ Chí Minh"}}</span>
            <span>Điện thoại :<b>{{ $setting->dienthoai ?? "0334202221"}}</b></span>
        </div>
        <div class="info">
            <div class="w-50">
                <ul>
                    <li>Khách hàng : <span>{{$order->hoten}}</span></li>
                    <li>Thời gian : <span>{{ format_date($order->ngaytao)}}</span></li>
                    <li>Thu ngân : <span>{{ getNameLog() ?? 'Admin' }}</span></li>
                </ul>
            </div>
            <div class="w-50">
                <ul>
                    <li>Số hoá đơn : <span>{{$order->madh}}</span></li>
                </ul>
            </div>
        </div>
        <div class="detail">
            <table class="table table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Sản phẩm</th>
                        <th>Size</th>
                        <th>Số lượng</th>
                        <th>Giá bán</th>
                        <th class="td-right">Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @if($orderDetail && count($orderDetail))
                    @foreach($orderDetail as $key => $value)
                    <tr>
                        <td>
                            {{ $key + 1}}
                        </td>
                        <td>{{ $value->product->tensp ?? "[]" }} ({{ $value->size->size_name }})</td>
                        <td>
                            {{ $value->size->size_name }}
                        </td>
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
                        <td class="td-right">
                            {{currency_format($value->giaban )}}
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    <tr class="td-right">
                        <td colspan="4" class="td-right">
                            <b> Tổng tiền sản phẩm:</b>
                        </td>
                        <td colspan="2" class="td-right">
                            <span>
                                {{currency_format($order->tongdonhang)}}</span>
                        </td>
                    </tr>
                    @if($order->Coupon)
                    <tr class="td-right">
                        <td colspan="4">
                            <b>Giảm giá:</b><span>
                        </td>
                        <td colspan="2" class="td-right">
                            <span style="white-space: nowrap;">
                                @if($order->Coupon->loaigiam === 1)
                                <span> {{ $order->Coupon->giamgia}}%
                                    ( -{{currency_format($order->tongdonhang *  $order->Coupon->giamgia / 100)}}
                                    )</span>
                                @else
                                <span>- {{currency_format($order->Coupon->giamgia) }}</span>@endif</span>
                        </td>
                    </tr>
                    @endif
                    <tr class="td-right">
                        <td colspan="4">
                            <b>Tiền phí vận chuyển:</b>
                        </td>
                        <td colspan="2" class="td-right">
                            <span>
                                @if($order->id_feeship && $order->Ship->feeship)+
                                {{currency_format($order->Ship->feeship)}}@else{{currency_format(0)}}@endif</span>
                        </td>
                    </tr>
                    <tr class="td-right">
                        <td colspan="4">
                            <b>Thành tiền:</b>
                        </td>
                        <td colspan="2" class="td-right">
                            <span>{{currency_format($order->tongtien)}}</span>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

        <div class="footer">
            <h4>!! Xin cảm ơn quý khách !!</h2>
        </div>
    </div>
</body>

</html>