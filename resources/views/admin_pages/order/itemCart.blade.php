@if(Session::has('cartAD') != null && Session::get('cartAD')->products)
<table class="table table-pro">
    <thead>
        <tr>
            <th>Sản phẩm</th>
            <th>Size</th>
            <th>Số lượng</th>
            <th>Giảm giá</th>
            <th>Giá bán</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach(Session::get('cartAD')->products as $key => $value)
        <tr>
            <th style="display: flex;gap: 10px;">
                <img style="width: 70px;height: 70px;object-fit: cover;border-radius: 4px;"
                    src="{{ asset('uploads/product').'/'.$value['productInfo']->hinhanh }}" class="img-fluid" alt="" />
                <span>{{$value['productInfo']->tensp}}</span>
            </th>
            <th>
                <span class="badge badge-primary">{{$value['size']->size_name}}</span>
            </th>
            <th>
                <div class="quanty-updown">
                    <button class="arrow down" data-id="{{$key}}"><i class="fa fa-minus"
                            aria-hidden="true"></i></button>
                    <input readonly class="arrow-input arrow-input-{{$key}}" min='1' max='100' data-key="{{$key}}"
                        data-size="{{$value['size']->id}}" value="{{$value['quanty']}}" />
                    <button class="arrow up" data-id="{{$key}}"><i class="fa fa-plus" aria-hidden="true"></i></button>

                </div>
            </th>
            <th>
                @if(count($value['productInfo']->Coupon) > 0)
                {{'( - '.currency_format($value['productInfo']->Coupon[0]->giamgia, ($value['productInfo']->Coupon[0]->loaigiam === 2) ? 'đ' : '%').' )'}}
                @else
                0đ
                @endif
            </th>
            <th>
                @if(count($value['productInfo']->Coupon) > 0)
                <span class="price-old">
                    {{ currency_format($value['productInfo']->giagoc + $value['size']->price)}}
                </span>
                @endif
                <span>
                    {{ currency_format($value['productInfo']->giaban + $value['size']->price)}}
                </span>
            </th>
            <th>
                <a href="#" id="deletecart" data-id="{{$key}}" class="btn btn-danger mgr-5">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </a>
            </th>
        </tr>
        @endforeach
        <tr class="td-right">
            <td colspan="4" class="td-right pr-10pt">
                <b> Tổng tiền sản phẩm :</b>
            </td>
            <td class="td-left" colspan="2">
                <span class="">
                    {{currency_format(Session::get('cartAD')->totalPrice)}}</span>
            </td>
        </tr>
        <tr class="td-right">
            <td colspan="4" class=" pr-10pt">
                <b>Tiền phí vận chuyển : </b>
            </td>
            <td class="td-left" colspan="2">
                <span class="">
                    @if(Session::get('cartAD')->feeShip)
                    + {{currency_format(Session::get('cartAD')->feeShip)}}
                    @else
                    {{currency_format(0)}}
                    @endif
                </span>
            </td>
        </tr>
        <tr class="td-right">
            <td colspan="4" class="pr-10pt">
                <b>Thành tiền :</b>
            </td>
            <td class="td-left" colspan="2">
                <span class="">
                    <?php
                    $price = (Session::get('cartAD')->totalPrice + Session::get('cartAD')->feeShip);
                    ?>
                    {{currency_format($price > 0 ? $price : 0)}}
                </span>
            </td>
        </tr>
    </tbody>
</table>
@endif