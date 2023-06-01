<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Form</title>
</head>
<body>
    <div class="main">
        <form action="{{route('authregister')}}" method="post">
			@csrf
            <div class="form-group">
                <label for="">email</label>
                <input type="text" class="form-control" id="email" name="email">
            </div>
			<div class="form-group">
                <label for="">password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
			<div class="form-group">
                <label for="">confirm password</label>
                <input type="password" class="form-control">
            </div>
			<button type="submit" style="color: aqua">tao tai khoan</button>
        </form>
    </div>
</body>

</html>
