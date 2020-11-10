<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
Dear {{$user->getName()}}

<p>Your password has been reset. You can now <a href='{{route('signin')}}'>login to your account</a> with your new password.</p>

<p>Thank you</p>
</body>
</html>
