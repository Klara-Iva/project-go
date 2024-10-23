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
    <div class="nav-bar">
    <div>
        <div class="welcome-message">
            Welcome {{ $user->name }} ({{ $user->role->role_name }})
        </div>
        <div class="vacation-days-message">
            Unused vacation days: {{$user->annual_leave_days}}
        </div>
    </div>
    <div class="top-right-buttons">
        <a href="{{ route('allrequests') }}" class="btn btn-primary">View all requests</a>
        <a href="{{ route('user.showResetPasswordForm') }}" class="btn btn-primary">Reset Password</a>
        <a href="{{ route('vacation.request.view') }}" class="btn btn-primary">New vacation Request</a>
        <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display:inline;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
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
    </div>
    <div class="page-container">

        <div class="search-container">

            <div class="search-form">
                <h1>Search Users</h1>
                <form action="{{ route('managers.dashboard') }}" method="GET">
                    <input type="text" name="search_term" placeholder="Search..." value="{{ request('search_term') }}">
                    <label><input type="checkbox" name="search_columns[]" value="name" {{ in_array('name', request('search_columns', [])) ? 'checked' : '' }}> Name</label>
                    <label><input type="checkbox" name="search_columns[]" value="email" {{ in_array('email', request('search_columns', [])) ? 'checked' : '' }}> Email</label>
                    <button class="button-search" type="submit">Search</button>
                </form>
            </div>
        </div>

        <div class="container">
            <h1>Team Members</h1>

            <div class="header-row">

                <div class="header-text">
                    <a
                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc']) }}">
                        Name
                        @if ($sortBy == 'name')
                            @if ($sortOrder == 'asc')
                                &#9650;
                            @else
                                &#9660;
                            @endif
                        @endif
                    </a>
                </div>
                <div class="header-text">
                    <a
                        href="{{ request()->fullUrlWithQuery(['sort_by' => 'role', 'sort_order' => $sortOrder == 'asc' ? 'desc' : 'asc']) }}">
                        Role
                        @if ($sortBy == 'role')
                            @if ($sortOrder == 'asc')
                                &#9650;
                            @else
                                &#9660;
                            @endif
                        @endif
                    </a>
                </div>
            </div>

            <div class="form-group">
                <label for="perPageSelect">Prikaži po stranici:</label>
                <select id="perPageSelect" class="form-control" onchange="changePerPage()">
                    <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>

            <script>
                function changePerPage() {
                    var perPage = document.getElementById('perPageSelect').value;
                    var url = new URL(window.location.href);
                    url.searchParams.set('per_page', perPage);
                    window.location.href = url.toString();
                }
            </script>

            <div class="pagination-container">
                {{ $teamUsers->appends(request()->input())->links() }}
            </div>


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

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>