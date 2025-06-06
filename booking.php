<?php
session_start();
require_once 'includes/layout.php';
require_once 'includes/auth_secure.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Please login first to book tickets');
        window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
    </script>";
    exit();
}

// Check if id parameter exists
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid movie ID");
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Validate movie ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?error=invalid_movie");
    exit();
}

$id = (int)$_GET['id']; // Cast to integer for security
$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);

// In the PHP section, add this date validation:
$selectedDate = isset($_GET['date']) ? $_GET['date'] : '';
if ($selectedDate && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
    header("Location: booking.php?id=$id");
    exit();
}

// Get movie details using prepared statement
$movieQuery = "SELECT * FROM movietable WHERE movieID = ?";
$stmt = mysqli_prepare($link, $movieQuery);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$movieResult = mysqli_stmt_get_result($stmt);
$movie = mysqli_fetch_array($movieResult);

if (!$movie) {
    header("Location: index.php?error=movie_not_found");
    exit();
}

// Get available dates using prepared statement
$dateQuery = "SELECT DISTINCT scheduleDate FROM scheduletable 
             WHERE movieID = ? AND scheduleDate >= CURDATE()
             ORDER BY scheduleDate";
$stmt = mysqli_prepare($link, $dateQuery);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$dateResult = mysqli_stmt_get_result($stmt);


// Get available times if date is selected
$times = [];
$theatres = [];
$selectedDate = isset($_GET['date']) ? $_GET['date'] : '';
$selectedTime = isset($_GET['time']) ? $_GET['time'] : '';



if ($selectedDate) {
    // Get distinct times for selected date using prepared statement
    $timeQuery = "SELECT DISTINCT scheduleTime FROM scheduletable 
                 WHERE movieID = ? AND scheduleDate = ?
                 ORDER BY scheduleTime";
    $stmt = mysqli_prepare($link, $timeQuery);
    mysqli_stmt_bind_param($stmt, "is", $id, $selectedDate);
    mysqli_stmt_execute($stmt);
    $timeResult = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($timeResult)) {
        $times[] = $row['scheduleTime'];
    }

    // Get theatres for selected date and time
    if ($selectedTime) {
        $theatreQuery = "SELECT DISTINCT theatre FROM scheduletable 
                       WHERE movieID = ? 
                       AND scheduleDate = ?
                       AND scheduleTime = ?";
        $stmt = mysqli_prepare($link, $theatreQuery);
        $timeValue = str_replace('-', ':', $selectedTime);
        mysqli_stmt_bind_param($stmt, "iss", $id, $selectedDate, $timeValue);
        mysqli_stmt_execute($stmt);
        $theatreResult = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($theatreResult)) {
            $theatres[] = $row['theatre'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <link rel="stylesheet" href="style/styles.css"> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <title>Book <?php echo $movie['movieTitle']; ?> Now</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <style>
        .booking-container {
            max-width: 1000px;
            margin: 2rem auto;
            background: #1a1a1a;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            display: grid;
            grid-template-columns: 1fr 1fr;
            color: #fff;
            position: relative;
        }

        .booking-header {
            grid-column: 1 / span 2;
            background: #4cc9f0;
            padding: 1.5rem;
            text-align: center;
            color: #000;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .close-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            cursor: pointer;
            color: #fff;
            background: #d9534f;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .movie-poster {
            padding: 2rem;
            min-height: 450px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 1px solid #333;
        }

        .movie-poster img {
            width: 300px;
            height: 450px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .movie-poster {
                min-height: 300px;
            }

            .movie-poster img {
                width: 200px;
                height: 300px;
            }
        }

        .booking-content {
            padding: 2rem;
        }

        .movie-title {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: #4cc9f0;
            font-weight: bold;
        }

        .movie-info {
            margin-bottom: 2rem;
        }

        .movie-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .movie-info td {
            padding: 0.5rem 0;
            border-bottom: 1px solid #333;
        }

        .movie-info td:first-child {
            font-weight: bold;
            color: #4cc9f0;
            width: 30%;
        }

        .booking-form select,
        .booking-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 1rem;
            border: none;
            border-radius: 5px;
            background: #333;
            color: #fff;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4cc9f0;
            font-weight: bold;
        }

        .booking-form select:focus,
        .booking-form input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #4cc9f0;
        }

        .submit-btn {
            background: #4cc9f0;
            color: #000;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .submit-btn:hover {
            background: #3aa8d8;
        }

        @media (max-width: 768px) {
            .booking-container {
                grid-template-columns: 1fr;
            }

            .movie-poster {
                border-right: none;
                border-bottom: 1px solid #333;
            }
        }
    </style>
</head>

<body style="background-color:#0f0f0f;">
    <div class="booking-container">
        <div class="booking-header">
            <h1>RESERVE YOUR TICKET</h1>
        </div>
        <div class="close-btn" onclick="window.history.go(-1); return false;">
            <i class="fas fa-2x fa-times"></i>
        </div>
        <div class="movie-poster">
            <img src="<?php echo 'img/' . basename($movie['movieImg']); ?>" alt="Movie Poster">
        </div>
        <div class="booking-content">
            <div class="movie-title"><?php echo $movie['movieTitle']; ?></div>
            <div class="movie-info">
                <table>
                    <tr>
                        <td>GENRE</td>
                        <td><?php echo $movie['movieGenre']; ?></td>
                    </tr>
                    <tr>
                        <td>DURATION</td>
                        <td><?php echo $movie['movieDuration']; ?> minutes</td>
                    </tr>
                    <tr>
                        <td>RELEASE DATE</td>
                        <td><?php echo $movie['movieRelDate']; ?></td>
                    </tr>
                    <tr>
                        <td>DIRECTOR</td>
                        <td><?php echo $movie['movieDirector']; ?></td>
                    </tr>
                    <tr>
                        <td>ACTORS</td>
                        <td><?php echo $movie['movieActors']; ?></td>
                    </tr>
                </table>
            </div>
            <form class="booking-form" action="booking.php?id=<?php echo $id; ?>&date=<?php echo urlencode($selectedDate); ?>&time=<?php echo urlencode($selectedTime); ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="form-group">
                    <label for="dateSelect">Select Date:</label>
                    <select name="date" id="dateSelect" required onchange="updateTimes()">
                        <option value="" disabled selected>Choose a date</option>
                        <?php while ($date = mysqli_fetch_assoc($dateResult)): ?>
                            <option value="<?php echo $date['scheduleDate']; ?>"
                                <?php if ($selectedDate == $date['scheduleDate']) echo 'selected'; ?>>
                                <?php echo date('F j, Y', strtotime($date['scheduleDate'])); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <?php if ($selectedDate): ?>
                        <div class="form-group">
                            <label for="timeSelect">Select Time:</label>
                            <select name="time" id="timeSelect" required onchange="updateTheatres()">
                                <option value="" disabled selected>Choose a time</option>
                                <?php foreach ($times as $time): ?>
                                    <option value="<?php echo date('H-i', strtotime($time)); ?>"
                                        <?php if ($selectedTime == date('H-i', strtotime($time))) echo 'selected'; ?>>
                                        <?php echo date('g:i A', strtotime($time)); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php if ($selectedTime): ?>
                            <div class="form-group">
                                <label for="theatreSelect">Select Theater:</label>
                                <select name="theatre" id="theatreSelect" required>
                                    <option value="" disabled selected>Choose a theater</option>
                                    <?php foreach ($theatres as $theatre): ?>
                                        <option value="<?php echo $theatre; ?>">
                                            <?php echo ucfirst(str_replace('-', ' ', $theatre)); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="typeSelect">Select Type:</label>
                        <select name="type" id="typeSelect" required>
                            <option value="" disabled selected>Choose a type</option>
                            <option value="3d">3D</option>
                            <option value="2d">2D</option>
                            <option value="imax">IMAX</option>
                        </select>

                        <input placeholder="First Name" type="text" name="fName" required>
                        <input placeholder="Last Name" type="text" name="lName">
                        <input placeholder="Phone Number" type="text" name="pNumber" maxlength="10" required>

                        <button type="button" id="selectSeatBtn" class="submit-btn" style="margin-top:10px;"
                            <?php if (!$selectedDate || !$selectedTime || empty($theatres)) echo 'disabled'; ?>>SELECT SEAT</button>
                        <input type="hidden" id="selectedSeat" name="selectedSeat" required>

                        <button type="submit" name="submit" class="submit-btn" onclick="return validateForm()">CONFIRM BOOKING</button>
                    </div>
            </form>
            <script>
                function validateForm() {
                    const seats = document.querySelector('input[name="selectedSeat"]').value;
                    if (!seats) {
                        alert("Please select at least one seat");
                        document.getElementById('selectSeatBtn').click();
                        return false;
                    }
                    return true;
                }
            </script>
        </div>
    </div>

    <!-- Seat Selection Modal -->
    <div id="seatModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Select Your Seat</h2>
            <div class="seat-map">
                <div class="screen">SCREEN</div>
                <div class="seats-container" id="seatsContainer">
                    <!-- Seats will be generated here -->
                </div>
            </div>
            <div class="seat-info">
                <div class="seat-legend">
                    <div class="legend-item">
                        <div class="seat-available"></div>
                        <span>Available</span>
                    </div>
                    <div class="legend-item">
                        <div class="seat-booked"></div>
                        <span>Booked</span>
                    </div>
                    <div class="legend-item">
                        <div class="seat-selected"></div>
                        <span>Selected</span>
                    </div>
                </div>
                <div class="selected-seat">
                    Selected Seat: <span id="selectedSeatNumber">None</span>
                </div>
                <input type="hidden" id="selectedSeat" name="selectedSeat">
                <button type="button" class="confirm-seat-btn">Confirm Seat</button>
            </div>
        </div>
    </div>

    <style>
        /* Seat Selection Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            background-color: #1a1a1a;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #4cc9f0;
            width: 80%;
            max-width: 800px;
            border-radius: 10px;
            color: white;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: white;
        }

        .seat-map {
            margin: 20px 0;
        }

        .screen {
            background: #4cc9f0;
            color: #000;
            text-align: center;
            padding: 10px;
            margin-bottom: 30px;
            font-weight: bold;
            border-radius: 5px;
        }

        .seats-container {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 10px;
            justify-items: center;
        }

        .seat {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        }

        .seat-available {
            background-color: #4CAF50;
        }

        .seat-booked {
            background-color: #f44336 !important;
            cursor: not-allowed;
            position: relative;
        }

        .seat-booked::after {
            content: "✗";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 16px;
            pointer-events: none;
        }

        .seat-selected {
            background-color: #ffeb3b;
            color: #000;
        }

        .seat-info {
            margin-top: 20px;
            padding: 15px;
            background: #333;
            border-radius: 5px;
        }

        .seat-legend {
            display: flex;
            justify-content: space-around;
            margin-bottom: 15px;
        }

        .legend-item {
            display: flex;
            align-items: center;
        }

        .seat-available,
        .seat-booked,
        .seat-selected {
            width: 20px;
            height: 20px;
            border-radius: 3px;
            margin-right: 10px;
        }

        .selected-seat {
            text-align: center;
            margin: 15px 0;
            font-size: 18px;
        }

        .confirm-seat-btn {
            background: #4cc9f0;
            color: #000;
            border: none;
            padding: 10px 20px;
            width: 100%;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .confirm-seat-btn:hover {
            background: #3aa8d8;
        }
    </style>

    <script src="scripts/jquery-3.3.1.min.js"></script>
    <script src="scripts/script.js"></script>
    <script>
        function updateTimes() {
            const dateSelect = document.getElementById('dateSelect');
            const selectedDate = dateSelect.value;
            if (selectedDate) {
                const encodedDate = encodeURIComponent(selectedDate);
                window.location.href = `booking.php?id=<?php echo $id; ?>&date=${encodedDate}`;
            }
        }

        function updateTheatres() {
            const timeSelect = document.getElementById('timeSelect');
            const selectedTime = timeSelect.value;
            if (selectedTime) {
                const encodedDate = encodeURIComponent('<?php echo $selectedDate; ?>');
                const encodedTime = encodeURIComponent(selectedTime);
                window.location.href = `booking.php?id=<?php echo $id; ?>&date=${encodedDate}&time=${encodedTime}`;
            }
        }

        // Enable/disable seat selection button based on form completion
        function checkFormCompletion() {
            const dateSelected = document.getElementById('dateSelect').value;
            const timeSelected = document.getElementById('timeSelect')?.value;
            const theatreSelected = document.getElementById('theatreSelect')?.value;
            const selectSeatBtn = document.getElementById('selectSeatBtn');

            if (dateSelected && timeSelected && theatreSelected) {
                selectSeatBtn.disabled = false;
            } else {
                selectSeatBtn.disabled = true;
            }
        }

        // Initialize form validation and seat selection
        document.addEventListener('DOMContentLoaded', function() {
            // Check form completion on page load
            checkFormCompletion();

            // Add event listeners to form elements
            document.getElementById('dateSelect').addEventListener('change', checkFormCompletion);
            if (document.getElementById('timeSelect')) {
                document.getElementById('timeSelect').addEventListener('change', checkFormCompletion);
            }
            if (document.getElementById('theatreSelect')) {
                document.getElementById('theatreSelect').addEventListener('change', checkFormCompletion);
            }

            // Seat Selection Functionality
            const modal = document.getElementById('seatModal');
            const selectSeatBtn = document.getElementById('selectSeatBtn');
            selectSeatBtn.onclick = async function() {
                // Verify all required fields are selected
                const dateSelected = document.getElementById('dateSelect').value;
                const timeSelected = document.getElementById('timeSelect')?.value;
                const theatreSelected = document.getElementById('theatreSelect')?.value;

                if (dateSelected && timeSelected && theatreSelected) {
                    modal.style.display = 'block';
                    try {
                        await loadSeats();
                    } catch (error) {
                        console.error('Error loading seats:', error);
                        alert('Error loading seat information. Please try again.');
                        modal.style.display = 'none';
                    }
                } else {
                    alert('Please select date, time and theater first');
                }
            };
            document.querySelector('.close').onclick = function() {
                modal.style.display = 'none';
            };

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };

            async function loadSeats() {
                const seatsContainer = document.getElementById('seatsContainer');
                seatsContainer.innerHTML = '';

                // Get the current schedule details from the form
                const dateSelected = document.getElementById('dateSelect').value;
                const timeSelected = document.getElementById('timeSelect').value;
                const theatreSelected = document.getElementById('theatreSelect').value;
                const selectedSeats = document.querySelector('input[name="selectedSeat"]').value.split(',').filter(s => s);

                // First get the schedule ID
                const scheduleResponse = await fetch(`getScheduleId.php?date=${dateSelected}&time=${timeSelected.replace('-', ':')}&theatre=${theatreSelected}&movieId=<?php echo $id; ?>`);
                const scheduleData = await scheduleResponse.json();

                if (!scheduleData.scheduleId) {
                    alert('Error: Could not find schedule. Please try again.');
                    modal.style.display = 'none';
                    return;
                }

                const scheduleId = scheduleData.scheduleId;

                if (scheduleId > 0) {
                    // Fetch booked seats from the database
                    fetch(`getBookedSeats.php?scheduleId=${scheduleId}`)
                        .then(response => response.json())
                        .then(bookedSeats => {
                            // Create seat layout
                            for (let i = 1; i <= 100; i++) {
                                const seat = document.createElement('div');
                                const isBooked = bookedSeats.includes(i);
                                const isSelected = selectedSeats.includes(i.toString());

                                seat.className = isBooked ? 'seat seat-booked' :
                                    isSelected ? 'seat seat-selected' : 'seat seat-available';
                                seat.textContent = i;

                                if (!isBooked) {
                                    seat.onclick = function() {
                                        if (this.classList.contains('seat-selected')) {
                                            this.classList.remove('seat-selected');
                                            this.classList.add('seat-available');
                                        } else {
                                            this.classList.remove('seat-available');
                                            this.classList.add('seat-selected');
                                        }
                                        updateSelectedSeats();
                                    };
                                } else {
                                    seat.title = "This seat is already booked";
                                }
                                seatsContainer.appendChild(seat);
                            }
                        });
                } else {
                    alert('Please select date, time and theater first');
                    modal.style.display = 'none';
                }
            }

            function updateSelectedSeats() {
                const selectedSeats = Array.from(document.querySelectorAll('.seat-selected'))
                    .map(seat => seat.textContent)
                    .join(',');

                document.getElementById('selectedSeat').value = selectedSeats;
                document.getElementById('selectedSeatNumber').textContent =
                    selectedSeats || 'None';
            }

            document.querySelector('.confirm-seat-btn').onclick = function() {
                const selectedSeats = document.getElementById('selectedSeat').value;
                if (selectedSeats) {
                    modal.style.display = 'none';
                    // Update the hidden input in the main form
                    document.querySelector('input[name="selectedSeat"]').value = selectedSeats;
                } else {
                    alert('Please select at least one seat');
                }
            };
        });
    </script>
</body>

</html>

<?php
// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Existing form submission handling code
if (isset($_POST['submit'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // Debug output for token mismatch
        die("Invalid CSRF token. Expected: " . $_SESSION['csrf_token'] . " Received: " . ($_POST['csrf_token'] ?? 'none'));
    }
    // Input validation
    $errors = [];

    // Validate first name (required, letters only, 2-50 chars)
    $firstName = trim($_POST['fName'] ?? '');
    if (empty($firstName) || !preg_match('/^[a-zA-Z ]{2,50}$/', $firstName)) {
        $errors[] = "Please enter a valid first name (2-50 letters)";
    }

    // Validate last name (optional, but if provided must be letters only)
    $lastName = trim($_POST['lName'] ?? '');
    if (!empty($lastName) && !preg_match('/^[a-zA-Z ]*$/', $lastName)) {
        $errors[] = "Last name can only contain letters";
    }

    // Validate phone number (required, exactly 10 digits)
    $phone = trim($_POST['pNumber'] ?? '');
    if (empty($phone) || !preg_match('/^\d{10}$/', $phone)) {
        $errors[] = "Please enter a valid 10-digit phone number";
    }

    if (!empty($errors)) {
        echo "<script>alert('" . implode("\\n", $errors) . "'); window.history.back();</script>";
        exit();
    }

    $seatNumbers = array_filter(
        array_map('intval', explode(',', $_POST['selectedSeat'])),
        function ($num) {
            return $num > 0;
        }
    );

    if (empty($seatNumbers)) {
        echo "<script>alert('Please select at least one valid seat'); window.history.back();</script>";
        exit();
    }

    $scheduleId = 0;

    // Get schedule ID based on selected movie, date, time and theater using prepared statement
    $scheduleQuery = "SELECT scheduleID FROM scheduletable 
                     WHERE movieID = ? 
                     AND scheduleDate = ?
                     AND scheduleTime = ?
                     AND theatre = ?";
    $stmt = mysqli_prepare($link, $scheduleQuery);
    $time = str_replace('-', ':', $_POST["time"]);
    mysqli_stmt_bind_param($stmt, "isss", $id, $_POST["date"], $time, $_POST["theatre"]);
    mysqli_stmt_execute($stmt);
    $scheduleResult = mysqli_stmt_get_result($stmt);
    $schedule = mysqli_fetch_assoc($scheduleResult);

    if ($schedule) {
        $scheduleId = $schedule['scheduleID'];
        $_SESSION['current_schedule_id'] = $scheduleId;

        // Check if any seats are already booked
        $placeholders = implode(',', array_fill(0, count($seatNumbers), '?'));
        $checkQuery = "SELECT seat_number FROM seatbookings 
                      WHERE schedule_id = ? AND seat_number IN ($placeholders) AND is_booked = 1";
        $stmt = mysqli_prepare($link, $checkQuery);
        $types = str_repeat('i', count($seatNumbers));
        mysqli_stmt_bind_param($stmt, "i$types", $scheduleId, ...$seatNumbers);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $bookedSeats = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $bookedSeats[] = $row['seat_number'];
            }
            echo "<script>alert('Seat(s) " . implode(', ', $bookedSeats) . " are already booked! Please select different seats.');</script>";
            exit();
        }

        // Insert booking with user ID
        // First validate the user_id exists
        $user_check = "SELECT id FROM users WHERE id = ?";
        $stmt = mysqli_prepare($link, $user_check);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 0) {
            throw new Exception("Invalid user ID");
        }

        $insert_query = "INSERT INTO bookingtable 
            (movieName, bookingTheatre, bookingType, bookingDate, 
             bookingTime, bookingFName, bookingLName, bookingPNumber, user_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Start transaction for atomic operations
        mysqli_begin_transaction($link);

        try {
            // Insert booking using prepared statement
            $stmt = mysqli_prepare($link, $insert_query);
            $time = str_replace('-', ':', $_POST["time"]);
            mysqli_stmt_bind_param(
                $stmt,
                "ssssssssi",
                $movie['movieTitle'],
                $_POST["theatre"],
                $_POST["type"],
                $_POST["date"],
                $time,
                $_POST["fName"],
                $_POST["lName"],
                $_POST["pNumber"],
                $user_id
            );

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Booking failed: " . mysqli_error($link));
            }

            $bookingId = mysqli_insert_id($link);

            // Mark all selected seats as booked
            $bookSeatQuery = "INSERT INTO seatbookings 
                            (schedule_id, seat_number, is_booked, booking_id)
                            VALUES (?, ?, 1, ?)";
            $stmt = mysqli_prepare($link, $bookSeatQuery);

            foreach ($seatNumbers as $seatNumber) {
                mysqli_stmt_bind_param($stmt, "iii", $scheduleId, $seatNumber, $bookingId);
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Seat reservation failed for seat $seatNumber: " . mysqli_error($link));
                }
            }

            // Commit transaction if all succeeds
            mysqli_commit($link);

            echo "<script>
            alert('Booking successful for seat(s): " . implode(', ', $seatNumbers) . "');
            window.location.href = 'index.php';
            </script>";

            // Regenerate CSRF token after successful booking
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } catch (Exception $e) {
            // Rollback transaction on error
            mysqli_rollback($link);
            echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
            exit();
        }
    }

    mysqli_close($link);
}
?>