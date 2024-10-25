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
            Number of days you can request: <strong style=" font-size: 1.5em;">{{ $remainingVacationDays }}</strong>
        </div>


        <form method="POST" id="vacationRequestForm" action="{{ route('submitVacationRequest') }}">
            @csrf
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
            <div class="error" id="start_date_error"></div>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
            <div class="error" id="end_date_error"></div>

            <label for="days_off">Days Off:</label>
            <input type="number" id="days_off" name="days_off" value="{{ old('days_off') }}" required min="1">
            <div class="error" id="days_off_error"></div>

            <div class="form-button">
                <button type="submit" class="send-btn">Send Request</button>
            </div>
        </form>

    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#vacationRequestForm').on('submit', function (e) {
            e.preventDefault();

            $('.error').text('');

            $.ajax({
                url: "{{ route('submitVacationRequest') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert(response.message);
                },
                error: function (xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        if (errors.start_date) {
                            $('#start_date_error').text(errors.start_date[0]);
                        }
                        if (errors.end_date) {
                            $('#end_date_error').text(errors.end_date[0]);
                        }
                        if (errors.days_off) {
                            $('#days_off_error').text(errors.days_off[0]);
                        }
                        if (errors.error) {
                            alert(errors.error);
                        }
                    }
                }
            });
        });
    </script>
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