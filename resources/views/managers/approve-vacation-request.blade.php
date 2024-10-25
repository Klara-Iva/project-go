<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/approve-vacation-request.css') }}">

</head>

<body>

    <div class="container">
        <h2>Request Details</h2>
        <div class="detail"><strong>Requested by:</strong> <span>{{ $request->user->name }}</span></div>
        <div class="detail detail-right"><strong>Team/s:</strong><span>
                @foreach ($request->user->teams as $team)
                    {{ $team->name }}
                    @if (!$loop->last), @endif
                @endforeach
            </span></div>
        <div class="detail"><strong>Start date:</strong> <span>{{ $request->start_date }}</span></div>
        <div class="detail"><strong>End date:</strong> <span>{{ $request->end_date }}</span></div>
        <div class="detail"><strong>Duration:</strong> <span>{{ $request->days_requested }} days</span></div>
        <div class="detail"><strong>Project Manager Approval:</strong>
            <span>{{ $request->project_manager_approved }}</span>
        </div>
        <div class="detail"><strong>Team Leader Approval:</strong> <span>{{ $request->team_leader_approved }}</span>
        </div>
        <div class="detail"><strong>Status:</strong> <span
                data-status="{{ $request->status }}">{{ $request->status }}</span></div>
        <div class="form-group">
            @if($request->team_leader_comment)
                <p><strong>Team Leader's Comment:</strong> {{ $request->team_leader_comment }}</p>
            @endif

            @if($request->project_manager_comment)
                <p><strong>Project Manager's Comment:</strong> {{ $request->project_manager_comment }}</p>
            @endif
        </div>


        <form action="{{ route('vacation.approval', $request->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="comment">Comment (optional):</label>
                @if(Auth::user()->role_id == 2)
                    <textarea name="comment" id="comment" class="form-control"
                        rows="2">{{ $request->team_leader_comment }}</textarea>
                @elseif(Auth::user()->role_id == 3)
                    <textarea name="comment" id="comment" class="form-control"
                        rows="2">{{ $request->project_manager_comment }}</textarea>
                @endif
            </div>

            <div class="form-group">
                <label for="approval">Approval:</label><br>
                <div class="optionsform">
                    <input type="radio" id="approve" name="action" value="approved" required>
                    <label for="approve">Approve</label>
                    <input type="radio" id="reject" name="action" value="rejected" required>
                    <label for="reject">Reject</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form>


    </div>
</body>

</html>