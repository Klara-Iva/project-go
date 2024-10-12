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
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
            color: #ffffff;
            height: 100vh;
        }

        .container {
            background-color: rgba(35, 35, 35, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
            width: 100%;
            color: #ffffff;
        }

        .h1 {
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

        .card {
            background-color: rgba(0, 0, 0, 0.5);
            color: #ffffff;
            border-radius: 21px;
        }

        .card p {
            margin: 0;
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
    </style>
</head>

<body>

    <div class="top-right-buttons">
        <a href="{{ route('vacation.request.view') }}" class="btn btn-primary">New vacation Request</a>
        <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display:inline;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>



    <div class="welcome-message">
        Welcome {{ $user->name }} ({{ $user->role->role_name }})
    </div>

    <div class="container">

        @if (session('success'))
            <div class="alert alert-success success-alert">
                {{ session('success') }}
            </div>
        @endif
        <script>

            setTimeout(function () {
                var alert = document.querySelector('.success-alert');
                if (alert) {
                    alert.style.display = 'none';
                }
            }, 2000); 
        </script>

        <h1>Manager Dashboard</h1>
        <p>Dobrodošli na managerski panel!</p>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>