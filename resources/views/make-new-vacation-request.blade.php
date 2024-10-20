<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Vacation Request</title>
    <link rel="stylesheet" href="{{ asset('css/make-new-vacation-request.css') }}">
</head>

<body>

    @if ($errors->has('error'))
        <div id="error-popup" class="error-message">
            {{ $errors->first('error') }}
        </div>
    @endif


    <button class="back-btn" onclick="window.history.back();">Back</button>
    <form action="{{ route('logout') }}" method="POST" id="logout-form">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
    </form>

    <div class="container">
        <h2>New Vacation Request</h2>
        <div>
            Number of days you can request: {{ $remainingVacationDays }}
        </div>


        <form method="POST" action="{{ route('submitVacationRequest') }}">
            @csrf
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
            @error('start_date')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
            @error('end_date')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="days_off">Days Off:</label>
            <input type="number" id="days_off" name="days_off" value="{{ old('days_off') }}" required min="1">
            @error('days_off')
                <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit" class="send-btn">Send Request</button>
        </form>
    </div>


    <script>
        const errorPopup = document.getElementById('error-popup');
        if (errorPopup) {
            setTimeout(() => {
                errorPopup.classList.add('fade-out');
            }, 5000);
            setTimeout(() => {
                errorPopup.style.display = 'none';
            }, 6000);
        }
    </script>

</body>

</html>