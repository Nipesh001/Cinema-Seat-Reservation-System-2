<?php
require_once 'auth_check.php';
$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Schedule</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="../style/alert_styles.css">
    <link rel="stylesheet" href="../style/admin-styles.css">

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
            <ul>
                <li><i class="fas fa-sliders-h"></i><a href="dashboard.php">Dashboard</a></li>
                <li><i class="fas fa-ticket-alt"></i><a href="bookings.php">Bookings</a></li>
                <li><i class="fas fa-film"></i><a href="movies.php">Movies</a></li>
                <li><i class="fas fa-calendar-alt"></i><a href="schedule.php">Schedule</a></li>
                <li><i class="fas fa-envelope"></i><a href="messages.php">Messages</a></li>
            </ul>
        </div>
        <div class="admin-section admin-section2">
            <div class="admin-section-panel">
                <div class="admin-panel-section-header">
                    <h2>Movie Schedule</h2>
                    <i class="fas fa-calendar-alt" style="background-color: #3cbb6c"></i>
                </div>
                <div class="admin-panel-section-content">
                    <form action="" method="POST">
                        <select name="movieID" required>
                            <option value="">Select Movie</option>
                            <?php
                            $movies = mysqli_query($link, "SELECT * FROM movietable");
                            while ($movie = mysqli_fetch_array($movies)) {
                                echo '<option value="' . $movie['movieID'] . '">' . $movie['movieTitle'] . '</option>';
                            }
                            ?>
                        </select>
                        <select name="theatre" required>
                            <option value="">Select Theatre</option>
                            <option value="Main Hall">Main Hall</option>
                            <option value="VIP Hall">VIP Hall</option>
                            <option value="Secondary Hall">Secondary Hall</option>
                        </select>
                        <input type="date" name="scheduleDate" required>
                        <input type="time" name="scheduleTime" required>
                        <button type="submit" name="addSchedule" class="form-btn">Add Schedule</button>
                        <?php
                        if (isset($_POST['addSchedule'])) {
                            // Verify table exists first
                            $tableExists = mysqli_query($link, "SHOW TABLES LIKE 'scheduletable'");
                            if (mysqli_num_rows($tableExists) > 0) {
                                $stmt = $link->prepare("INSERT INTO scheduletable 
                                    (movieID, theatre, scheduleDate, scheduleTime)
                                    VALUES (?, ?, ?, ?)");
                                $stmt->bind_param(
                                    "isss",
                                    $_POST['movieID'],
                                    $_POST['theatre'],
                                    $_POST['scheduleDate'],
                                    $_POST['scheduleTime']
                                );

                                if ($stmt->execute()) {
                                    echo '<p class="admin-success">Schedule added successfully!</p>';
                                    header("Refresh:2");
                                } else {
                                    echo '<p class="admin-error">Error adding schedule: ' . $stmt->error . '</p>';
                                }
                                $stmt->close();
                            } else {
                                echo '<p class="admin-error">Schedule table not found - please contact administrator</p>';
                            }
                        }
                        ?>
                    </form>
                </div>
            </div>
            <div class="admin-section-panel">
                <div class="admin-panel-section-header">
                    <h2>Current Schedule</h2>
                    <i class="fas fa-calendar-alt" style="background-color: #3cbb6c"></i>
                </div>
                <div class="admin-panel-section-content">
                    <?php
                    // Check if scheduletable exists
                    $tableExists = mysqli_query($link, "SHOW TABLES LIKE 'scheduletable'");
                    if (mysqli_num_rows($tableExists) > 0) {
                        $sql = "SELECT s.*, m.movieTitle, m.movieImg FROM scheduletable s 
                                JOIN movietable m ON s.movieID = m.movieID
                                ORDER BY m.movieTitle, s.scheduleDate, s.scheduleTime";
                        if ($result = mysqli_query($link, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                $currentMovie = null;
                                while ($row = mysqli_fetch_array($result)) {
                                    if ($currentMovie != $row['movieID']) {
                                        // New movie - close previous div if exists
                                        if ($currentMovie !== null) {
                                            echo '</div>'; // Close schedule-times
                                            echo '</div>'; // Close movie-schedule
                                        }
                                        $currentMovie = $row['movieID'];
                                        echo '<div class="movie-schedule">';
                                        echo '<div class="movie-header">';
                                        echo '<img src="../' . $row['movieImg'] . '" style="height:80px">';
                                        echo '<h3>' . $row['movieTitle'] . '</h3>';
                                        echo '</div>';
                                        echo '<div class="schedule-times">';
                                    }
                                    echo '<div class="time-slot">';
                                    echo '<span class="theater">' . $row['theatre'] . '</span>';
                                    echo '<span class="datetime">' . date('M j', strtotime($row['scheduleDate'])) . ' at ' . date('g:i A', strtotime($row['scheduleTime'])) . '</span>';
                                    echo '<a href="deleteSchedule.php?id=' . $row['scheduleID'] . '" class="delete-schedule" onclick="return confirm(\'Delete this schedule?\')"><i class="fas fa-times"></i></a>';
                                    echo '</div>';
                                }
                                // Close last movie div
                                if ($currentMovie !== null) {
                                    echo '</div></div>';
                                }
                            } else {
                                echo '<h4 class="no-annot">No scheduled movies</h4>';
                            }
                        }
                    } else {
                        echo '<h4 class="no-annot">Schedule table not initialized</h4>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>