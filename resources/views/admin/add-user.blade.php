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
        }
    </style>
</head>

<body>

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

        <form method="POST" action="{{ route('user.save') }}">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="4" {{ old('role') == 'user' ? 'selected' : '' }}>Employee</option>
                    <option value="1" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="3" {{ old('role') == 'project_manager' ? 'selected' : '' }}>Project Manager</option>
                    <option value="2" {{ old('role') == 'team_leader' ? 'selected' : '' }}>Team Leader</option>
                </select>
                @error('role')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
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
            </div>
            <button type="submit" class="btn btn-submit">Add User</button>
        </form>
    </div>

</body>

</html>