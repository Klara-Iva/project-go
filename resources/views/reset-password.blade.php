<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-image: url('/images/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: 'Roboto', sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .form-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            margin-bottom: 1.5rem;
            font-size: 24px;
            color: #333;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #5b9dd9;
            outline: none;
            box-shadow: 0 0 5px rgba(91, 157, 217, 0.5);
        }

        .btn {
            margin-top: 15px;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .back-btn,
        .logout-btn {
            position: absolute;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            width: 100px;
        }

        .back-btn {
            background-color: #ff5e57;
            top: 20px;
            left: 20px;
        }

        .logout-btn {
            background-color: #333;
            top: 20px;
            right: 20px;
        }

        .text-danger {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
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