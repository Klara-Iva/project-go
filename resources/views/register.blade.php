<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>
    <h1>PROJECT G.O.</h1>
    <div class="container">
        <h2>Register</h2>

        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <label for="name">Name:</label>
            <input type="name" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Register</button>
        </form>
        <a href="{{ route('login') }}" class="btn btn-secondary" style="margin-top: 20px;">Login</a>
    </div>
</body>

</html>