<!DOCTYPE html>
<html>
<head>
    <title>Kaptan.com</title>
</head>
<body>
<form method="POST" action="{{ route('store-password') }}">
    @csrf
    <input  type="text" class="form-control" readonly name="reset_code" value="{{ $reset_code }}" required  autofocus>
   <label>New password</label>
    <input  type="password" class="form-control" name="new_password" value="" required  autofocus>
    <button type="submit" class="btn btn-primary">
    Save
    </button>
</form>

</body>
</html>