<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <style>
        body {
            background-color: lightblue;
            text-align: center;
        }

        .form-inp-res {
            background-color: white;
            text-align: center;
            font-weight: bold;
            width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>

<body>
    <div class="form-inp-res">
        <h3>Nhâp mật khẩu mới</h3>
        <div class="form-rs">
            <form action="{{route('handlerspw',$email)}}" method="post">
                @csrf
                <div class="form-controler">
                    <label for="">email</label>
                    <input type="text" name="emailrs" readonly value="{{ $email }}" placeholder="nhap email">
                </div>
                <div class="form-controler">
                    <label for="">password</label>
                    <input type="text" name="pw_inp" pplaceholder="nhap mat khau" minlength="10" maxlength="15">
                </div>
                <div class="form-controler">
                    <label for="">repassword</label>
                    <input type="text" name="repw_inp" placeholder="xac nan mat khau" minlength="10" maxlength="15">
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</body>

</html>
