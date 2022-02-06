<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email</title>
</head>
<body>
    <p>Dear <strong><?php echo e($attendee ['name']); ?>,</strong></p>
    <p>Please find your room details written below.</p>
    <p>Room Name: <strong><?php echo e($details->room ? $details->room->name : ''); ?><strong></p>
    <p>Check-in Date Time: <strong><?php echo e($details ['formatted_start_date']); ?></strong></p>
    <p>Check-out Date Time: <strong><?php echo e($details ['formatted_end_date']); ?></strong></p>
    <p>Thank you</p>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\intranet\resources\views/send-mail.blade.php ENDPATH**/ ?>