<?php
require_once 'auth_check.php';
$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);
$bookingsNo = mysqli_num_rows(mysqli_query($link, "SELECT DISTINCT bookingID FROM bookingtable"));
$messagesNo = mysqli_num_rows(mysqli_query($link, "SELECT * FROM feedbacktable"));
$moviesNo = mysqli_num_rows(mysqli_query($link, "SELECT * FROM movietable"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="../style/alert_styles.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
</head>

<body>
    <div class="admin-section-header">
        <div class="admin-logo">PREMIUM CINEMA</div>
        <div class="admin-login-info">
            <a href="#">Welcome, <?= htmlspecialchars($_SESSION['adminFullName'] ?? $_SESSION['admin_username'] ?? 'Admin') ?></a>
            <a href="./adminLogout.php" style="margin-left: 20px; color: #f44336; font-weight: bold; text-decoration: none;">Logout</a>
            <img class="admin-user-avatar" src="../img/avatar.png" alt="">
        </div>
    </div>
    <div class="admin-container">
        <div class="admin-section admin-section1">
            <ul class="admin-sidebar-nav">
                <li class="admin-nav-item">
                    <a href="dashboard.php" class="admin-nav-link">
                        <i class="fas fa-sliders-h"></i>Dashboard
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="bookings.php" class="admin-nav-link">
                        <i class="fas fa-ticket-alt"></i>Bookings
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="movies.php" class="admin-nav-link">
                        <i class="fas fa-film"></i>Movies
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="schedule.php" class="admin-nav-link">
                        <i class="fas fa-calendar-alt"></i>Schedule
                    </a>
                </li>
                <li class="admin-nav-item">
                    <a href="messages.php" class="admin-nav-link">
                        <i class="fas fa-envelope"></i>Messages
                    </a>
                </li>
            </ul>
        </div>
        <div class="admin-section admin-section2">
            <div class="admin-section-panel admin-section-stats">
                <div class="admin-section-stats-panel">
                    <i class="fas fa-ticket-alt" style="background-color: #cf4545"></i>
                    <h2 style="color: #cf4545"><?= $bookingsNo ?></h2>
                    <h3>Bookings</h3>
                </div>
                <div class="admin-section-stats-panel">
                    <i class="fas fa-film" style="background-color: #4547cf"></i>
                    <h2 style="color: #4547cf"><?= $moviesNo ?></h2>
                    <h3>Movies</h3>
                </div>
                <div class="admin-section-stats-panel">
                    <i class="fas fa-envelope" style="background-color: #3cbb6c"></i>
                    <h2 style="color: #3cbb6c"><?= $messagesNo ?></h2>
                    <h3>Messages</h3>
                </div>
            </div>
            <div class="admin-section-panel">
                <div class="admin-panel-section-header">
                    <h2>Recent Bookings</h2>
                    <i class="fas fa-ticket-alt" style="background-color: #cf4545"></i>
                </div>
                <div class="admin-panel-section-content">
                    <table class="bookings-container">
                        <thead>
                            <tr>
                                <th>Poster</th>
                                <th>Movie</th>
                                <th>Theater</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT b.*, m.movieImg FROM bookingtable b 
                                    JOIN movietable m ON b.movieName = m.movieTitle 
                                    ORDER BY bookingID DESC LIMIT 5";
                            if ($result = mysqli_query($link, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo '<tr>';
                                        echo '<td><img class="booking-poster" src="../' . $row['movieImg'] . '" alt="Movie Poster"></td>';
                                        echo '<td class="booking-movie">' . $row['movieName'] . '</td>';
                                        echo '<td class="booking-theater">' . $row['bookingTheatre'] . '</td>';
                                        echo '<td>' . $row['bookingDate'] . '</td>';
                                        echo '<td>' . $row['bookingFName'] . ' ' . $row['bookingLName'] . '</td>';
                                        echo '<td>' . $row['bookingPNumber'] . '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="no-annot">No recent bookings</td></tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if ($_SESSION['isSuperAdmin'] ?? false): ?>
                <div class="admin-section-panel">
                    <div class="admin-panel-section-header">
                        <h2>Create New Admin</h2>
                        <i class="fas fa-user-plus" style="background-color: #3cbb6c"></i>
                    </div>
                    <div class="admin-panel-section-content">
                        <form action="createAdmin.php" method="POST" class="admin-creation-form">
                            <input type="text" name="username" placeholder="Username" required>
                            <input type="password" name="password" placeholder="Password" required>
                            <input type="email" name="email" placeholder="Email" required>
                            <input type="text" name="fullname" placeholder="Full Name" required>
                            <div style="margin: 10px 0;">
                                <select name="isSuperAdmin" id="isSuperAdmin" required style="padding: 10px; width: 250px; text-align: center;">
                                    <option value="" selected>Admin Type:</option>
                                    <option value="0" >Normal Admin</option>
                                    <option value="1">Super Admin</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Admin</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
