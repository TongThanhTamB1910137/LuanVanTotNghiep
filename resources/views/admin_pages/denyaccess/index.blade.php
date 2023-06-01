<!DOCTYPE html>
<html>

<head>
    <title>Access Denied</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body {
            background-color: black;
            color: white;
        }

        h1 {
            color: red;
        }

        h6 {
            color: red;
            text-decoration: underline;
        }

        a {
            text-decoration: none;
        }

        a:hover {
            color: yellow;
        }
    </style>
</head>

<body>
    <div class="w3-display-middle">
        <h1 class="w3-jumbo w3-animate-top w3-center"><code>Không truy cập được</code></h1>
        <hr class="w3-border-white w3-animate-left" style="margin:auto;width:50%">
        <h3 class="w3-center w3-animate-right">Bạn không có quyền truy cập!</h3>
        <h3 class="w3-center w3-animate-zoom">🚫🚫🚫🚫</h3>
        <a href="{{route('showDashboard')}}">Quay lại</a>
    </div>
</body>

</html>