<!DOCTYPE html>
<html lang="en">

<head>
    <style>
    .card {
        width: 500px;
        height: 100%;
        border: 3px dashed #f2f0f0;
        border-radius: 4px;
        display: block;
        margin: 0 auto;
        margin-top: 20px;
    }

    .header,
    .footer {
        padding: 10px;
        background-color: #f2f0f0;
    }

    .body {
        text-align: center;
    }

    .title {
        font-size: 18px;
        font-weight: 600;
        font-style: italic;
        text-decoration: underline;
    }

    span {
        color: red;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        @if(isset($coupon) && count($coupon) > 0)
        @foreach($coupon as $value)
        <div class="card">
            <div class="header">
                Mã khuyến mãi Drink Coffee gửi đến khách hàng.
            </div>
            <div class="body">
                <p class="title">{{$value->ten}}</p>
                {{'Khuyến mãi : ( - '.currency_format($value->giamgia, ($value->loaigiam === 2) ? 'đ' : '%').' )'}}
                <p>{{$value->mota}}
                </p>
            </div>
            <div class="footer">
                <h4>Mã khuyễn mãi: {{$value->code}}</h4>
                <span>Ngày bắt đầu: <?= date("d/m/Y", strtotime($value->ngaybd)) ?></span> -
                <span>Ngày kết thúc: <?= date("d/m/Y", strtotime($value->ngaykt)) ?></span>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</body>

</html>