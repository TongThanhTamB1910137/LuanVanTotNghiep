<div style="margin: 0 12px;font-size: 16px;">
    <img src="{{ $message->embed($img_url.'img/logo.png')}}" style="width: 100px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
    margin-bottom: 10px;
    margin: 0 auto 12px;
    display: block;" alt="" />
    <div style="font-weight: 600;font-size: 25px;">
        Cảm ơn bạn đã đặt hàng của chúng tôi!
    </div>
    <div>
        <h2 style="font-weight: 600;">Xin chào {{$order->hoten}},</h2>
        <p>Chúng tôi rất vui vì bạn đã tin tường lựa chọn sản phẩm và dịch vụ của cửa hàng.</p>
        <p style="color: red; font-weight: 600;">Đơn hàng <span style="font-style: italic;">{{$order->madh}}</span> của
            bạn đã
            bị huỷ.</span></p>
        <p> Với lý do : <span style="font-weight: 600; font-size: 18px">{{$reason}}</span></p>
        <p>Hy vọng sẽ được phục vụ quý khách tốt ơn trong những đơn hàng sau.</p>
        <div class="card">
            <div class="card-header">
                <h5 style="font-size: 18px;
    font-weight: 600;
    margin: 8px 0;
    display: block;
    background: #eee;
    padding: 7px;
    border-radius: 3px;">
                    Thông tin các sản phẩm</h5>
            </div>
            <div class="card-body">
                <table class="table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style=" text-align: left;">Sản phẩm</th>
                            <th style=" text-align: left;">Size</th>
                            <th style=" text-align: left;">Số lượng</th>
                            <th style=" text-align: left;">Giá bán</th>
                            <th style="text-align: right;">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($orderDetail && count($orderDetail))
                        @foreach($orderDetail as $value)
                        <tr style="margin: 8px 0px;">
                            <td style="padding: 4px 0;display: flex;align-items: center;gap: 20px;">
                                <img src="{{ $message->embed($img_url.'uploads/product/'.$value->product->hinhanh)}}"
                                    style=" width: 70px;height: 70px;object-fit: cover;border-radius: 4px; margin-right: 8px;"
                                    alt="" />
                                <span>{{ $value->product->tensp ?? "[]" }}</span>
                            </td>
                            <td style="padding: 4px 0;">{{ $value->size->size_name }}</td>
                            <td style="padding: 4px 0;">
                                {{ $value->soluong }}
                            </td>
                            <td style="padding: 4px 0;">
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
                            <td style="text-align: right;">
                                {{currency_format($value->giaban)}}
                            </td>
                        </tr>
                        @endforeach
                        @endif
                        <tr style="text-align: right;margin:8px 0;">
                            <td colspan="4" style="text-align: right;">
                                <b> Tổng tiền sản phẩm :</b>
                            </td>
                            <td style="text-align: right;">
                                <span>
                                    {{currency_format($order->tongdonhang)}}</span>
                            </td>
                        </tr>
                        @if($order->Coupon)
                        <tr style="text-align: right;margin:8px 0;">
                            <td colspan="4">
                                <b>Giảm giá :</b><span>
                            </td>
                            <td style="text-align: right;">
                                <span class="no-wrap">
                                    @if($order->Coupon->loaigiam === 1)
                                    <span>{{ $order->Coupon->giamgia}}%
                                        ( -
                                        {{currency_format($order->tongdonhang *  $order->Coupon->giamgia / 100)}}
                                        )</span>
                                    @else
                                    <span>-{{currency_format($order->Coupon->giamgia)}}</span>
                                    @endif
                                </span>
                            </td>
                        </tr>
                        @endif
                        <tr style="text-align: right;margin:8px 0;">
                            <td colspan="4">
                                <b>Tiền phí vận chuyển : </b>
                            </td>
                            <td style="text-align: right;">
                                <span>
                                    @if($order->id_feeship && $order->Ship->feeship)
                                    + {{currency_format($order->Ship->feeship)}}
                                    @else
                                    {{currency_format(0)}}
                                    @endif
                                </span>
                            </td>
                        </tr>
                        <tr style="text-align: right;margin:8px 0;">
                            <td colspan="4">
                                <b>Thành tiền :</b>
                            </td>
                            <td style="text-align: right;">
                                <span>
                                    {{currency_format($order->tongtien)}}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>