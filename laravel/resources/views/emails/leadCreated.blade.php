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
<p>Congratulations, User First Name! We received this lead alert from your listing website at PropertySpot.net/123MainStreet/:</p>


<div>Name: {{$lead->name}}</div>
<div>Email: {{$lead->email}}</div>
<div>Phone: {{$lead->phone}}</div>
<div>Message: {{nl2br($lead->message)}}</div>

<p>Thanks!</p>

<div>The PropertySpot Support Team</div>
<div>Need support? Email support@propertyspot.net</div> 

<p>You received this email because you signed up for PropertySpot.net. <a href='https://propertyspot.net/signin'>Click here</a> to manage your email preferences.</p>
</body>
</html>
