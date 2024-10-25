<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/user-details.css') }}">
</head>

<body>
    <a href="{{ route('admin.dashboard') }}" class="btn-back">Back</a>

    <script>
        if (window.performance && window.performance.navigation.type === 2) {
            window.location.reload(true);
        }
    </script>

    <div class="container">
        <h1>User Details</h1>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>

            @if($user->role_id != 1)
                <div class="form-group">
                    <label for="role" class="radio-label">Role:</label>
                    @foreach($roles as $role)
                        <div>
                            <label>
                                <input type="radio" name="role_id" value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'checked' : '' }}>
                                {{ $role->role_name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <div class="form-group">
                    <label for="teams" class="checkbox-label">Teams:</label>
                    <div>
                        @foreach($teams as $team)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="team_ids[]" value="{{ $team->id }}" {{ in_array($team->id, $userTeams) ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $team->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <button type="submit" class="btn btn-primary">Update User</button>
        </form>

        <form action="{{ route('user.destroy', $user->id) }}" method="POST"
            onsubmit="return confirm('Are you sure you want to delete this user?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete User</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>