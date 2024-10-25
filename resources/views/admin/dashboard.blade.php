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
    <div class="nav-bar">
        <div class="welcome-message">
            Welcome {{ $user->name }} ({{ $user->role->role_name }})
        </div>
        <div class="top-right-buttons">
            <a href="{{ route('allUsers') }}" class="btn btn-primary">Show all users</a>
            <a href="{{ route('user.showResetPasswordForm') }}" class="btn btn-primary">Reset Password</a>
            <a href="{{ route('user.add') }}" class="btn btn-primary">Add user</a>
            <a href="{{ route('logout') }}" class="btn btn-logout"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
    <div class="page-container">
        <div class="search-container">

            <div class="search-form">
                <h1>Search Users</h1>
                <form action="{{ route('admin.dashboard') }}" method="GET">
                    <input type="text" name="search_term" placeholder="Search..." value="{{ request('search_term') }}">
                    <label><input type="checkbox" name="search_columns[]" value="name" {{ in_array('name', request('search_columns', [])) ? 'checked' : '' }}> Name</label>
                    <label><input type="checkbox" name="search_columns[]" value="email" {{ in_array('email', request('search_columns', [])) ? 'checked' : '' }}> Email</label>
                    <button class="button-search" type="submit">Search</button>
                </form>
            </div>
        </div>

        <div class="user-container">


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
                <h1>All Users:</h1>
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
                    {{ $users->appends(request()->input())->links() }}
                </div>

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
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>