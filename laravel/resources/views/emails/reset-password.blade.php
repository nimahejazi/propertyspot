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

<p>Here is password reset link:</p>


<a href='https://propertyspot.net/reset-password?token={{$user->reset_token}}'>Reset My Password</a>

<p>This link is valid for 30 minutes.</p>

<p>If you haven't request password reset, you can ignore this email.</p>

<p>Thank you</p>
</body>
</html>
