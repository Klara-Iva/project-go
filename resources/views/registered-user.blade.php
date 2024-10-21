<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting for Assignment</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>
    <form action="{{ route('logout') }}" method="POST" id="logout-form">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>
    <div class="message-container">
        <h1>You're waiting to be assigned role and team</h1>
    </div>
</body>

</html>