<!DOCTYPE html>
<html>
<head>
    <title>Kaptan.com</title>
</head>
<body>
<h1>{{ $details['title'] }}</h1>
<p>There was recently a request to change the password for your account.

    If you requested this password change,please reset your password here:
</p>
<a href="{{$details['link']}}">Reset password</a>
<p>If you did not make this request, you can ignore this message and your password will remain the same.</p>
<p>Thank you</p>
</body>
</html>