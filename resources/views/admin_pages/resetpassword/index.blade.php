<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset password</title>
    <style>
        body {
            background-color: lightblue
        }

        .form-reset-pass {
            background-color: white;
            height: auto;
            text-align: center;
            padding: 40px;
        }
    </style>
</head>

<body>
    <div class="form-reset-pass">
        <h3>RESET PASSWORD</h3>
        <div class="form-input">
            <form action="{{route('sendmailreset')}}" method="post">
                @csrf
                <div class="form-control">
                    <label for="">Nhập email</label>
                    <input type="email" name="emailreset">
                </div>
                <button type="submit"
                    style="margin-top:20px;padding-left:20px;padding-right:20px;text-align: center">Send</button>
            </form>
        </div>
    </div>
</body>

</html>
