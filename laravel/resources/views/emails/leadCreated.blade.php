<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Lead from Your PropertySpot.net Website</title>
</head>
<body>
<p>Congratulations, {{$user->getName()}}! You received a new lead from your listing website at propertyspot.net/{{$listing->slug}}:</p>


<div>Name: {{$lead->name}}</div>
<div>Email: {{$lead->email}}</div>
<div>Phone: {{$lead->phone}}</div>
<div>Message: {{nl2br($lead->message)}}</div>

<p>Thanks!</p>

<div>The PropertySpot Support Team</div>
<div>Need support? Email us anytime at support@propertyspot.net</div> 

<p>You received this email because you signed up for PropertySpot.net. <a href='https://propertyspot.net/signin'>Sign in here</a> to manage your account.</p>
</body>
</html>
