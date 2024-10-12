<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Vacation Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('/images/background.jpg');
            background-size: cover;
            background-position: center;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            color: #333;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            margin-top: 15px;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .send-btn {
            background-color: #4CAF50;
            color: white;
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
    </style>
</head>

<body>


    <button class="back-btn" onclick="window.history.back();">Back</button>
    <form action="{{ route('logout') }}" method="POST" id="logout-form">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>

    <div class="container">
        <h2>New Vacation Request</h2>
        <div>
            Number of days you have: {{ $user->annual_leave_days }}
        </div>


        <form method="POST" action="{{ route('submitVacationRequest') }}">
            @csrf
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>
            <label for="days_off">Days Off:</label>
            <input type="number" id="days_off" name="days_off" required min="1">
            <button type="submit" class="send-btn">Send Request</button>
        </form>
    </div>


</body>

</html>