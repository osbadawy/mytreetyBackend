<!DOCTYPE html>
<html>
<head>
    <title>Product Report</title>
</head>
<body>
    <p><b>Product:</b> {{ $details['product_name'] }} (ID:{{ $details['product_id'] }})</p>
    <p><b>Name:</b> {{ $details['name'] }}</p>
    <p><b>Email:</b> {{ $details['email'] }}</p>
    <p><b>Message:</b> {{ $details['msg'] }}</p>

</body>
</html>
