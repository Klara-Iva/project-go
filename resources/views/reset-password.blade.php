<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/reset-password.css') }}">
</head>

<body>
    <button class="back-btn" onclick="window.history.back();">Back</button>
    <form action="{{ route('logout') }}" method="POST" id="logout-form">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>

    <div class="container">
        <div class="form-container">
            <h1>Reset Your Password</h1>
            <form action="{{ route('user.resetPassword') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                    @error('new_password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="new_password_confirmation" class="form-control"
                        required>
                    @error('new_password_confirmation')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn">Save</button>
            </form>
        </div>
    </div>
</body>


</html>