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
Dear {{$user->getName()}},

<p>This is confirmation that your password was recently reset. You can <a href='{{route('signin')}}'>sign in</a> to your account anytime with your new password.</p> 

<p>If you did not reset your password, please contact us immediately at <a href='mailto:support@propertyspot.net'>support@propertyspot.net</a>.</p>

<p>Thanks!</p>

<p>The PropertySpot Support Team</p>
</body>
</html>
