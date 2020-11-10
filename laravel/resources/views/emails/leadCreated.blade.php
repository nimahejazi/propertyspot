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

You got a new request:

<div>Name: {{$lead->name}}</div>
<div>Email: {{$lead->email}}</div>
<div>Phone: {{$lead->phone}}</div>
<div>Message: {{nl2br($lead->message)}}</div>

<p>Thank you</p>
</body>
</html>
