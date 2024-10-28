<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/vacation-request-details.css') }}">
    <style>
        .alert-success {
            position: absolute;
            top: 20px;
            position: 0 0;
            z-index: 1050;
            width: 500px;
        }

        .alert-danger {
            position: fixed;
            top: 20px;
            position: 0 0;
            z-index: 1050;
            width: 300px;
        }
    </style>
</head>

<body>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

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
        <form action="{{ route('deleteVacationRequest', $vacationRequest->id) }}" method="POST"
            onsubmit="return confirm('Are you sure you want to delete this vacation request?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Request</button>
        </form>
    </div>
</body>

</html>