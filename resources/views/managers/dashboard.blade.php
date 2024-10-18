<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('/images/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin-top: 50px;
            margin-bottom: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            color: #ffffff;
        }

        .container {
            background-color: rgba(35, 35, 35, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
            width: 100%;
            max-width: 900px;
            color: #ffffff;
        }

        h1 {
            color: #ffffff;
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.7em;
        }

        p {
            text-align: center;
            color: #ffffff;
            margin-bottom: 30px;
        }

        .top-right-buttons {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .top-right-buttons .btn {
            margin-left: 10px;
        }

        .btn-logout {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }

        .success-alert {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1050;
            width: 300px;
        }

        .alert-message {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            width: 300px;
        }

        .card {
            background-color: rgba(0, 0, 0, 0.5);
            color: #ffffff;
            border-radius: 21px;
            margin-bottom: 20px;
            width: 100%;
        }

        .card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
        }

        .details {
            display: flex;
            justify-content: space-between;
            width: 100%;
            gap: 20px;
        }

        .card-text {
            margin: 0;
            padding-right: 10px;
            flex: 1;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .welcome-message {
            text-align: center;
            font-size: 1.5em;
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .header-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 15px;
            border-radius: 21px;
            margin-bottom: 10px;
        }

        .header-row .header-text {
            text-align: center;
            padding: 10px;
            font-weight: bold;
            color: #fff;
        }

        .row-data {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            align-items: center;
            padding: 10px 15px;
        }

        .row-data .card-text {
            text-align: center;
            padding: 10px;
            font-size: 1.1em;
        }

        .row-data .btn {
            margin-left: 10px;
        }

        .vacation-days-message {
            text-align: center;
            font-size: 1.2em;
            position: absolute;
            top: 50px;
            left: 20px;
        }

        .btn-view {
            background-color: #007bff;
            border-color: #007bff;
            width: 70%;
            color: white;
        }
    </style>
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