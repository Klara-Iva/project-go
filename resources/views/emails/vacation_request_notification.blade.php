<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Vacation Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        .header {
            background-color: #a83273;
            color: white;
            padding: 10px 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin: 0;
        }

        h2 {
            font-size: 20px;
            color: #333;
            margin: 20px 0 10px;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            color: #555;
            margin: 10px 0;
        }

        .team-info {
            background-color: #e7f3fe;
            border-left: 4px solid #2196F3;
            padding: 10px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #999;
            text-align: center;
        }

        .thank-you {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Project-GO</h1>
        </div>
        <h2>New Vacation Request Submitted</h2>
        <p>Dear {{ $recipientName }},</p>
        <p>User <strong>{{ $user->name }}</strong> has submitted a vacation request.</p>

        <div class="team-info">
            <h3>Team Information</h3>
            <p><strong>Team(s):</strong> {{ implode(', ', $teamNames) }}</p>
            <p><strong>Request Date:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
        </div>

        <p>Please review the request at your earliest convenience.</p>

        <div class="thank-you">
            <p>Thank you for your attention!</p>
        </div>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Project-GO. All rights reserved.</p>
    </div>
</body>

</html>