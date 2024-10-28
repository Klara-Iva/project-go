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
            Number of days you can request: <strong style="font-size: 1.5em;">{{ $remainingVacationDays }}</strong>
        </div>

        <form method="POST" id="vacationRequestForm" action="{{ route('submitVacationRequest') }}">
            @csrf
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
            <div class="error" id="start_date_error"></div>

            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
            <div class="error" id="end_date_error"></div>

            <p>Selected days off:<span id="calculated_days_off" style="font-size: 1.5em;">0</span></p>
            <input type="hidden" id="days_off" name="days_off" value="0">
            <div class="error" id="days_off_error"></div>

            <div class="form-button">
                <button type="submit" class="send-btn">Send Request</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function isWeekend(date) {
            const day = date.getDay();
            return day === 6 || day === 0;
        }

        function calculateWorkingDays(startDate, endDate) {
            let count = 0;
            let currentDate = new Date(startDate);

            while (currentDate <= endDate) {
                if (!isWeekend(currentDate)) {
                    count++;
                }
                currentDate.setDate(currentDate.getDate() + 1);
            }
            return count;
        }

        function updateDaysOff() {
            const startDateInput = document.getElementById("start_date");
            const endDateInput = document.getElementById("end_date");
            const startDateError = document.getElementById("start_date_error");
            const endDateError = document.getElementById("end_date_error");
            const daysOffField = document.getElementById("days_off");
            const calculatedDaysOffSpan = document.getElementById("calculated_days_off");

            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            startDateError.textContent = '';
            endDateError.textContent = '';

            if (startDate && endDate && startDate <= endDate) {
                if (isWeekend(startDate)) {
                    startDateError.textContent = "Start date falls on a weekend.";
                }
                if (isWeekend(endDate)) {
                    endDateError.textContent = "End date falls on a weekend.";
                }

                const workingDays = calculateWorkingDays(startDate, endDate);
                daysOffField.value = workingDays;
                calculatedDaysOffSpan.textContent = workingDays;
            } else {
                daysOffField.value = 0;
                calculatedDaysOffSpan.textContent = 0;
            }
        }

        document.getElementById("start_date").addEventListener("focus", updateDaysOff);
        document.getElementById("end_date").addEventListener("focus", updateDaysOff);
        document.getElementById("start_date").addEventListener("change", updateDaysOff);
        document.getElementById("end_date").addEventListener("change", updateDaysOff);
    </script>

    <script>
        $('#vacationRequestForm').on('submit', function (e) {
            e.preventDefault();

            $('.error').text('');

            $.ajax({
                url: "{{ route('submitVacationRequest') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    console.log(response.message);
                    window.location.href = document.referrer;
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