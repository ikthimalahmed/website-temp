<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email</title>
</head>
<body>
    <p>Dear <strong>{{$attendee ['name']}},</strong></p>
    <p>Please find your room details written below.</p>
    <p>Room Name: <strong>{{$details->room ? $details->room->name : '' }}<strong></p>
    <p>Check-in Date Time: <strong>{{$details ['formatted_start_date']}}</strong></p>
    <p>Check-out Date Time: <strong>{{$details ['formatted_end_date']}}</strong></p>
    <p>Thank you</p>
</body>
</html>
