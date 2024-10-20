<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }}'s Vacation Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/user-requests.css') }}">
</head>

<body>

    <div class="container">
        <h1 class="header" style="color: white;">{{ $user->name }}'s Vacation Requests</h1>

        <a href="{{ route('managers.dashboard') }}" class="btn btn-secondary btn-back">Back to Dashboard</a>

        @if ($vacationRequests->isEmpty())
            <p>No vacation requests found for this user.</p>
        @else
            <div class="list-group">
                @foreach ($vacationRequests as $request)
                    <div class="card">
                        <div class="card-body">
                            <div class="details">
                                <p class="card-text"><strong>Start Date:</strong> {{ $request->start_date }}</p>
                                <p class="card-text"><strong>End Date:</strong> {{ $request->end_date }}</p>
                                <p class="card-text"><strong>Days Requested:</strong> {{ $request->days_requested }} days</p>
                                <p class="card-text"><strong>Status:</strong>
                                    <span
                                        style="color: {{ $request->status === 'approved' ? 'green' : ($request->status === 'rejected' ? 'red' : 'black') }};">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </p>
                                <p class="card-text"><strong>Team Leader Approval:</strong>
                                    {{ ucfirst($request->team_leader_approved) }}</p>
                                <p class="card-text"><strong>Project Manager Approval:</strong>
                                    {{ ucfirst($request->project_manager_approved) }}</p>
                            </div>
                            <a href="{{ route('request.details', $request->id) }}" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>