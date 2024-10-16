<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('/images/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Arial', sans-serif;
            color: white;
        }

        .container {
            background-color: rgba(35, 35, 35, 0.7);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 600px;
            margin-top: 90px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }

        .btn-submit {
            background-color: #007bff;
            color: white;
            width: 100%;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            color: white;
        }

        .form-control {
            background-color: #555;
            color: white;
            border: 1px solid #666;
        }

        .form-control:focus {
            background-color: #666;
            border-color: #007bff;
            color: #ffffff;
        }

        .form-check-label {
            color: white;
        }

        .text-danger {
            color: #ff4d4d;
            font-size: 0.875em;
            margin-top: 5px;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .back-btn {
            background-color: #007bff;
            border-color: #007bff;
            top: 20px;
            right: 140px;
        }

        .btn-logout {
            background-color: #333;
            top: 20px;
            right: 20px;
        }

        .back-btn,
        .btn-logout {
            top: 20px;
            position: absolute;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            width: 100px;
        }

        .back-btn:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="top-right-buttons">
        <a href="{{ route('admin.dashboard') }}" class="back-btn">Back</a>
        <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display:inline;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>

    <div class="container">
        <h1>Add New User</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('user.save') }}">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}"
                    required>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" id="role" name="role_id" required>
                    <option value="4" {{ old('role_id') == 4 ? 'selected' : '' }}>Employee</option>
                    <option value="1" {{ old('role_id') == 1 ? 'selected' : '' }}>Admin</option>
                    <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>Project Manager</option>
                    <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>Team Leader</option>
                </select>
            </div>

            <div class="form-group">
                <label for="teams" class="checkbox-label">Teams:</label>
                <div>
                    @foreach($teams as $team)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="team_ids[]" value="{{ $team->id }}">
                            <label class="form-check-label">{{ $team->name }}</label>
                        </div>
                    @endforeach
                </div>
                @error('team_ids')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-submit">Add User</button>
        </form>
    </div>

</body>

</html>