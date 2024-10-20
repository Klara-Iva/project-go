<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/managers-dashboard.css') }}">
</head>

<body>
    <div class="top-right-buttons">
        <a href="{{ route('allrequests') }}" class="btn btn-primary">View all requests</a>
        <a href="{{ route('user.showResetPasswordForm') }}" class="btn btn-secondary">Reset Password</a>
        <a href="{{ route('vacation.request.view') }}" class="btn btn-primary">New vacation Request</a>
        <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display:inline;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>

    <div class="welcome-message">
        Welcome {{ $user->name }} ({{ $user->role->role_name }})
    </div>
    <div class="vacation-days-message">
        Unused vacation days: {{$user->annual_leave_days}}
    </div>

    @if (session('success'))
        <div class="alert alert-success success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('alert'))
        <div class="alert alert-danger alert-message">
            {{ session('alert') }}
        </div>
    @endif

    <script>
        setTimeout(function () {
            var successAlert = document.querySelector('.success-alert');
            if (successAlert) {
                successAlert.style.display = 'none';
            }
        }, 2000);

        setTimeout(function () {
            var alertMessage = document.querySelector('.alert-message');
            if (alertMessage) {
                alertMessage.style.display = 'none';
            }
        }, 5500);
    </script>

    <div class="container">
        <h1>Team Members</h1>

        <div class="header-row">
            <div class="header-text">Name</div>
            <div class="header-text">Role</div>
            <div class="header-text">Vacation Requests</div>
        </div>

        <div class="container2 mt-4">
            @foreach ($teamUsers as $teamUser)
                <div class="row-data">
                    <p class="card-text">{{ $teamUser->name }}</p>
                    <p class="card-text">{{ optional($teamUser->role)->role_name }}</p>
                    <a href="{{ route('user.requests', $teamUser->id) }}" class="btn btn-view">View Requests</a>
                </div>
            @endforeach

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>