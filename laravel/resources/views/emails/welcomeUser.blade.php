<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome to PropertySpot.net</title>
</head>
<body>
Welcome, {{$user->email}}

<p>Thank you for choosing us to host your property website! We look forward to providing you with blazingly fast technology for your listings! Your new account information is below:</p>

Your PropertySpot username is: {{$user->email}}<br>
Sign in here: <a href='https://propertyspot.net/signin'>https://propertyspot.net/signin</a><br>

<p>Need to reset your password? You can do it anytime here: <a href='https://propertyspot.net/forgot-password'>Reset My Password</a></p>

<p>Thanks!</p>

The PropertySpot Support Team<br>
Need support? Email us anytime at <a href='mailto:support@propertyspot.net'>support@propertyspot.net</a><br>

<p>You received this email because you signed up for PropertySpot.net. <a href='https://propertyspot.net/signin'>Sign in here</a> to manage your account.</p>

</body>
</html>
