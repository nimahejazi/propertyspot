<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PropertySpot.net Password Reset</title>
</head>
<body>
Dear {{$user->getName()}},

<p>Here is your password reset link:</p>


<a href='https://propertyspot.net/reset-password?token={{$user->reset_token}}'>Reset My Password</a>

<p>This link is valid for 30 minutes. If you did not request a password reset, you can safely disregard this email.</p>

<p>Thanks!</p>

<p>The PropertySpot Support Team</p>
<p>Need support? Email us anytime at <a href='mailto:support@propertyspot.net'>support@propertyspot.net</a></p> 

</body>
</html>
