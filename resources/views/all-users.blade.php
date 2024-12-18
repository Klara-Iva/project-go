<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/all-users.css') }}">
</head>

<body>
    <div class="header-row">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-back">Back</a>
    </div>

    <div class="page-container">

        <div class="search-form">
            <h1>Search Users</h1>
            <form action="{{ route('users.search') }}" method="GET">
                <input type="text" name="search_term" placeholder="Search..." value="{{ request('search_term') }}">

                <label><input type="checkbox" name="search_columns[]" value="name" {{ in_array('name', request('search_columns', [])) ? 'checked' : '' }}> Name</label>
                <label><input type="checkbox" name="search_columns[]" value="email" {{ in_array('email', request('search_columns', [])) ? 'checked' : '' }}> Email</label>
                <label><input type="checkbox" name="search_columns[]" value="role" {{ in_array('role', request('search_columns', [])) ? 'checked' : '' }}> Role</label>
                <label><input type="checkbox" name="search_columns[]" value="teams" {{ in_array('teams', request('search_columns', [])) ? 'checked' : '' }}> Teams</label>
                <label><input type="checkbox" name="search_columns[]" value="vacationRequests" {{ in_array('vacationRequests', request('search_columns', [])) ? 'checked' : '' }}> Vacation
                    Requests</label>

                <button type="submit" class=" button-search">Search</button>
            </form>

            <div class="download-buttons">
                <form method="GET" action="{{ route('users.search.download.csv') }}">
                    <input type="hidden" name="search_term" value="{{ request('search_term') }}">
                    @foreach (request('search_columns', []) as $column)
                        <input type="hidden" name="search_columns[]" value="{{ $column }}">
                    @endforeach
                    <button type="submit" class="btn btn-primary">Download CSV</button>
                </form>

                <form method="GET" action="{{ route('users.search.download.pdf') }}">
                    <input type="hidden" name="search_term" value="{{ request('search_term') }}">
                    @foreach (request('search_columns', []) as $column)
                        <input type="hidden" name="search_columns[]" value="{{ $column }}">
                    @endforeach
                    <button type="submit" class="btn btn-primary">Download PDF</button>
                </form>
            </div>
        </div>

        <div class="user-container">
            <h1>All Users</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Teams</th>
                        <th>Vacation Requests</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role->role_name }}</td>
                            <td>
                                @foreach ($user->teams as $team)
                                    {{ $team->name }}
                                    @if (!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>
                                @if($user->vacationRequests && $user->vacationRequests->count() > 0)
                                    @foreach ($user->vacationRequests as $vacation)
                                        {{ $vacation->start_date }} - {{ $vacation->end_date }}
                                        @if (!$loop->last), @endif
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>