<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtered Users</title>
</head>

<body>

    <body>
        <div class="user-container">
            <table>
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
                                @else
                                    No vacation requests for this user
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>

</html>