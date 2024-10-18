<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            margin-top: 20px;
            margin-bottom: 190px;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-image: url('/images/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .detail {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .form-group {
            margin-top: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .detail span {
            font-weight: bold;
            color: #333;
        }

        .detail span[data-status="approved"] {
            color: #28a745;
        }

        .detail span[data-status="rejected"] {
            color: #dc3545;
        }

        input[type="radio"] {
            margin-right: 10px;
            vertical-align: middle;
            cursor: pointer;
        }

        button.btn {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 16px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button.btn:hover {
            background-color: #0056b3;
        }

        textarea.form-control {
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        textarea.form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .form-group p {
            font-size: 14px;
            color: #6c757d;
        }

        .detail span[data-status="approved"] {
            color: #28a745;
            font-weight: bold;
            text-transform: uppercase;
        }

        .detail span[data-status="rejected"] {
            color: #dc3545;
            text-transform: uppercase;
            font-weight: bold;
        }
    </style>

</head>

<body>

    <div class="container">
        <h2>Request Details</h2>
        <div class="detail"><strong>Requested by:</strong> <span>{{ $vacationRequest->user->name }}</span></div>
        <div class="detail"><strong>Start date:</strong> <span>{{ $vacationRequest->start_date }}</span></div>
        <div class="detail"><strong>End date:</strong> <span>{{ $vacationRequest->end_date }}</span></div>
        <div class="detail"><strong>Duration:</strong> <span>{{ $vacationRequest->days_requested }} days</span></div>
        <div class="detail"><strong>Project Manager Approval:</strong>
            <span>{{ $vacationRequest->project_manager_approved }}</span>
        </div>
        <div class="detail"><strong>Team Leader Approval:</strong>
            <span>{{ $vacationRequest->team_leader_approved }}</span>
        </div>
        <div class="detail"><strong>Status:</strong> <span
                data-status="{{ $vacationRequest->status }}">{{ $vacationRequest->status }}</span></div>
        <div class="form-group">
            @if($vacationRequest->team_leader_comment)
                <p><strong>Team Leader's Comment:</strong> {{ $vacationRequest->team_leader_comment }}</p>
            @endif

            @if($vacationRequest->project_manager_comment)
                <p><strong>Project Manager's Comment:</strong> {{ $vacationRequest->project_manager_comment }}</p>
            @endif
        </div>
    </div>
</body>

</html>