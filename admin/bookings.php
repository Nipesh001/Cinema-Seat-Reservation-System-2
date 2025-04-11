<?php
require_once 'auth_check.php';
$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);

// Check if bookingSeats column exists, if not create it
$result = mysqli_query($link, "SHOW COLUMNS FROM bookingTable LIKE 'bookingSeats'");
if (mysqli_num_rows($result) == 0) {
    mysqli_query($link, "ALTER TABLE bookingTable ADD COLUMN bookingSeats VARCHAR(255) NOT NULL DEFAULT 'N/A'");
}

$bookingsNo = mysqli_num_rows(mysqli_query($link, "SELECT * FROM bookingTable"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="../style/alert_styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

</head>

<body>
    <div class="admin-section-header">
        <div class="admin-logo">PREMIUM CINEMA</div>
        <div class="admin-login-info">
            <a href="#">Welcome, Admin</a>
            <img class="admin-user-avatar" src="../img/avatar.png" alt="">
        </div>
    </div>
    <div class="admin-container">
        <div class="admin-section admin-section1">
            <ul>
                <li><i class="fas fa-sliders-h"></i><a href="dashboard.php">Dashboard</a></li>
                <li><i class="fas fa-ticket-alt"></i><a href="bookings.php">Bookings</a></li>
                <li><i class="fas fa-film"></i><a href="movies.php">Movies</a></li>
                <li><i class="fas fa-calendar-alt"></i><a href="schedule.php">Schedule</a></li>
                <li><i class="fas fa-envelope"></i><a href="messages.php">Messages</a></li>
            </ul>
        </div>
        <div class="admin-section admin-section2">
            <div class="admin-section-panel admin-section-stats">
                <div class="admin-section-stats-panel">
                    <i class="fas fa-ticket-alt" style="background-color: #cf4545"></i>
                    <h2 style="color: #cf4545"><?= $bookingsNo ?></h2>
                    <h3>Bookings</h3>
                </div>
            </div>
            <div class="admin-section-panel">
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                    unset($_SESSION['success']);
                }
                ?>
                <div class="admin-panel-section-header">
                    <h2>All Bookings</h2>
                    <i class="fas fa-ticket-alt" style="background-color: #cf4545"></i>
                </div>
                <div class="admin-panel-section-content">
                    <table class="bookings-container">
                        <thead>
                            <tr>
                                <th>Poster</th>
                                <th>Movie</th>
                                <th>Theater</th>
                                <th>Date/Time</th>
                                <th>Seat</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Base query without seat information
                            $sql = "SELECT b.*, m.movieImg FROM bookingTable b 
                                    JOIN movieTable m ON b.movieName = m.movieTitle 
                                    ORDER BY b.bookingID DESC";

                            // Try to get seat information if seatbookings table exists
                            $seatInfoAvailable = false;
                            $tableCheck = mysqli_query($link, "SHOW TABLES LIKE 'seatbookings'");
                            if (mysqli_num_rows($tableCheck) > 0) {
                                try {
                                    // Try with common column names
                                    // First get column names from seatbookings table
                                    $seatColumns = mysqli_query($link, "SHOW COLUMNS FROM seatbookings");
                                    $bookingIdColumn = 'booking_id'; // default
                                    $seatNumberColumn = 'seat_number'; // default

                                    while ($col = mysqli_fetch_assoc($seatColumns)) {
                                        if (stripos($col['Field'], 'booking') !== false) {
                                            $bookingIdColumn = $col['Field'];
                                        }
                                        if (stripos($col['Field'], 'seat') !== false) {
                                            $seatNumberColumn = $col['Field'];
                                        }
                                    }

                                    $sql = "SELECT 
                                            MIN(b.bookingID) as bookingID,
                                            b.movieName,
                                            b.bookingTheatre,
                                            b.bookingDate,
                                            b.bookingTime,
                                            b.bookingFName,
                                            b.bookingLName,
                                            b.bookingPNumber,
                                            m.movieImg,
                                            GROUP_CONCAT(DISTINCT s.$seatNumberColumn SEPARATOR ', ') AS seatNumbers
                                           FROM bookingTable b 
                                           JOIN movieTable m ON b.movieName = m.movieTitle 
                                           LEFT JOIN seatbookings s ON s.$bookingIdColumn = b.bookingID
                                           GROUP BY 
                                            b.movieName,
                                            b.bookingDate,
                                            b.bookingTime,
                                            b.bookingFName,
                                            b.bookingLName,
                                            b.bookingPNumber
                                           ORDER BY MIN(b.bookingID) DESC";
                                    $seatInfoAvailable = true;
                                } catch (Exception $e) {
                                    // Fallback to base query if error occurs
                                    $sql = "SELECT b.*, m.movieImg FROM bookingTable b 
                                            JOIN movieTable m ON b.movieName = m.movieTitle 
                                            ORDER BY b.bookingID DESC";
                                }
                            }
                            if ($result = mysqli_query($link, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo '<tr>';
                                        echo '<td><img class="booking-poster" src="../' . $row['movieImg'] . '" alt="Movie Poster"></td>';
                                        echo '<td class="booking-movie">' . $row['movieName'] . '</td>';
                                        echo '<td class="booking-theater">' . $row['bookingTheatre'] . '</td>';
                                        echo '<td>' . $row['bookingDate'] . '<span class="time-display">' . $row['bookingTime'] . '</span>' . '</td>';
                                        echo '<td>';
                                        if ($seatInfoAvailable) {
                                            echo !empty($row['seatNumbers']) ? $row['seatNumbers'] : 'No seats booked';
                                        } else {
                                            echo 'Seat info unavailable';
                                        }
                                        echo '</td>';
                                        echo '<td>' . $row['bookingFName'] . ' ' . $row['bookingLName'] . '</td>';
                                        echo '<td>' . $row['bookingPNumber'] . '</td>';
                                        echo '<td class="booking-actions">';
                                        echo '<a href="deleteBooking.php?id=' . $row['bookingID'] . '" onclick="return confirm(\'Are you sure you want to delete booking #' . $row['bookingID'] . '?\')">';
                                        echo '<i class="fas fa-trash" title="Delete booking"></i></a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="8" class="no-annot">No bookings found</td></tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>