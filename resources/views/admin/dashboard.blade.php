<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('/images/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
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
            max-width: 1400px;
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
            margin-right: 10px;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 15px;
            border-radius: 21px;
            margin-bottom: 10px;
        }

        .header-row .header-text {
            flex: 1;
            text-align: center;
            padding: 0;
            font-weight: bold;
            color: #fff;
        }

        .header-row .header-text:nth-child(1),
        .header-row .header-text:nth-child(2),
        .header-row .header-text:nth-child(3) {
            width: 33.33%;
            text-align: center;
        }

        .user-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 15px;
            border-radius: 21px;
            margin-bottom: 10px;
        }

        .user-row .user-text {
            flex: 1;
            text-align: center;
            padding: 0;
            color: #fff;
        }

        .user-row .user-text:nth-child(1),
        .user-row .user-text:nth-child(2),
        .user-row .user-text:nth-child(3) {
            width: 33.33%;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="top-right-buttons">
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