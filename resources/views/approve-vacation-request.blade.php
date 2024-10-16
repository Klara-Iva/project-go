<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
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

        .btn-approve {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
        }

        .btn-reject {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Request Details</h2>
        <div class="detail"><strong>Requested by:</strong> <span>{{ $request->user->name }}</span></div>
        <div class="detail"><strong>Start date:</strong> <span>{{ $request->start_date }}</span></div>
        <div class="detail"><strong>Duration:</strong> <span>{{ $request->days_requested }} days</span></div>
        <div class="detail"><strong>Project Manager Approval:</strong>
            <span>{{ $request->project_manager_approved }}</span>
        </div>
        <div class="detail"><strong>Team Leader Approval:</strong> <span>{{ $request->team_leader_approved }}</span>
        </div>
        <div class="detail"><strong>Status:</strong> <span>{{ $request->status }}</span></div>
        <div class="buttons">
            <form action="{{ route('vacation.approve', $request->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-approve">Approve</button>
            </form>
            <form action="{{ route('vacation.reject', $request->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn-reject">Reject</button>
            </form>
        </div>
    </div>
</body>

</html>