<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
</head>

<body>

    <div class="top-right-buttons">
        <a href="{{ route('allUsers') }}" class="btn btn-secondary">Show all users</a>
        <a href="{{ route('user.showResetPasswordForm') }}" class="btn btn-secondary">Reset Password</a>
        <a href="{{ route('user.add') }}" class="btn btn-primary">Add user</a>
        <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display:inline;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>

    <div class="welcome-message">
        Welcome {{ $user->name }} ({{ $user->role->role_name }})
    </div>

    <div class="container">

        <h1>All Users:</h1>

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

        <div class="header-row">
            <div class="header-text">Name</div>
            <div class="header-text">Role</div>
            <div class="header-text">Details</div>
        </div>

        <div class="container2 mt-4">
            @if ($users->isEmpty())
                <p>No users to show.</p>
            @else
                @foreach ($users as $user)
                    <div class="user-row">
                        <div class="user-text">{{ $user->name }}</div>
                        <div class="user-text">{{ $user->role->role_name }}</div>
                        <div class="user-text">
                            <a href="{{ route('user.details', $user->id) }}" class="btn btn-primary">More Details</a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>