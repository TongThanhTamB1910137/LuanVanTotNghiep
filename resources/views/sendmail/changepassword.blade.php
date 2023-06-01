<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    Mật khẩu đã được cập nhật thành cônng
    <br>
    Thời gian: <?php
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    echo date('h:i:sa'),"<br>";
    echo ' Ngày: ';
    echo date('Y-m-d'); ?>
    <h3 style="font-weight: bold">Mật khẩu mới của bạn là:{{$newpass}}</h3>
</body>

</html>
