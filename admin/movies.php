<?php
require_once 'auth_check.php';
$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);
$moviesNo = mysqli_num_rows(mysqli_query($link, "SELECT * FROM movietable"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Movies</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="../style/alert_styles.css">
    <link rel="stylesheet" href="../style/admin-styles.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <style>
        /* Movie description textarea styling */
        textarea[name="movieDescription"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 14px;
            margin-bottom: 15px;
            resize: vertical;
            min-height: 100px;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        textarea[name="movieDescription"]:focus {
            border-color: #4cc9f0;
            outline: none;
            background-color: #fff;
            box-shadow: 0 0 0 2px rgba(76, 201, 240, 0.2);
        }
    </style>
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
            <div class="admin-section-column">
                <div class="admin-section-panel admin-section-stats">
                    <div class="admin-section-stats-panel">
                        <i class="fas fa-film" style="background-color: #4547cf"></i>
                        <h2 style="color: #4547cf"><?= $moviesNo ?></h2>
                        <h3>Movies</h3>
                    </div>
                </div>
                <div class="admin-section-panel">
                    <div class="admin-panel-section-header">
                        <h2>Add New Movie</h2>
                        <i class="fas fa-film" style="background-color: #4547cf"></i>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input placeholder="Title" type="text" name="movieTitle" required>
                        <input placeholder="Genre" type="text" name="movieGenre" required>
                        <input placeholder="Duration (minutes)" type="number" name="movieDuration" required>
                        <input placeholder="Release Date" type="date" name="movieRelDate" required>
                        <input placeholder="Director" type="text" name="movieDirector" required>
                        <input placeholder="Actors" type="text" name="movieActors" required>
                        <textarea placeholder="Movie Description" name="movieDescription" rows="4" required></textarea>
                        <input placeholder="Trailer Link (YouTube URL)" type="url" name="movieTrailerLink" required>
                        <input type="file" name="movieImg" accept="image/*" required>
                        <button type="submit" name="submit" class="form-btn">Add Movie</button>
                        <?php
                        if (isset($_POST['submit'])) {
                            // File upload handling (same as before)
                            $targetDir = "../img/";
                            $fileName = basename($_FILES["movieImg"]["name"]);
                            $targetFile = $targetDir . $fileName;
                            $uploadOk = 1;
                            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                            $check = getimagesize($_FILES["movieImg"]["tmp_name"]);
                            if ($check === false) {
                                echo '<p class="admin-error">File is not an image.</p>';
                                $uploadOk = 0;
                            }

                            if ($_FILES["movieImg"]["size"] > 5000000) {
                                echo '<p class="admin-error">Image is too large (max 5MB).</p>';
                                $uploadOk = 0;
                            }

                            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                                echo '<p class="admin-error">Only JPG, JPEG, PNG files allowed.</p>';
                                $uploadOk = 0;
                            }

if ($uploadOk == 1) {
    if (isset($_FILES["movieImg"]["tmp_name"]) && is_uploaded_file($_FILES["movieImg"]["tmp_name"])) {
        if (move_uploaded_file($_FILES["movieImg"]["tmp_name"], $targetFile)) {
            $stmt = $link->prepare("INSERT INTO movietable 
                (movieImg, movieTitle, movieGenre, movieDuration, movieRelDate, movieDirector, movieActors, movieDescription, movieTrailerLink)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $imgPath = "img/" . $fileName;
            $stmt->bind_param(
                "sssisssss",
                $imgPath,
                $_POST["movieTitle"],
                $_POST["movieGenre"],
                $_POST["movieDuration"],
                $_POST["movieRelDate"],
                $_POST["movieDirector"],
                $_POST["movieActors"],
                $_POST["movieDescription"],
                $_POST["movieTrailerLink"]
            );

            if ($stmt->execute()) {
                echo '<p class="admin-success">Movie added successfully!</p>';
                header("Refresh:2");
            } else {
                echo '<p class="admin-error">Error: ' . $stmt->error . '</p>';
            }
            $stmt->close();
        } else {
            $error = error_get_last();
            echo '<p class="admin-error">Error on uploading file. ' . htmlspecialchars($error['message'] ?? 'Unknown error') . '</p>';
        }
    } else {
        echo '<p class="admin-error">Uploaded file is not valid.</p>';
    }
}
                        }
                        ?>
                    </form>
                </div>
                <div class="admin-section-panel">
                    <div class="admin-panel-section-header">
                        <h2>Current Movies</h2>
                        <i class="fas fa-film" style="background-color: #4547cf"></i>
                    </div>
                    <div class="admin-panel-section-content">
                        <table class="movies-container">
                            <thead>
                                <tr>
                                    <th>Poster</th>
                                    <th>Title</th>
                                    <th>Genre</th>
                                    <th>Duration</th>
                                    <th>Director</th>
                                    <th>Actors</th>
                                    <th>Description</th>
                                    <th>Trailer</th>
                                    <th>Release Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM movietable ORDER BY movieID DESC";
                                if ($result = mysqli_query($link, $sql)) {
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo '<tr>';
                                            echo '<td><img class="movie-poster" src="../' . $row['movieImg'] . '"></td>';
                                            echo '<td class="movie-title">' . $row['movieTitle'] . '</td>';
                                            echo '<td class="movie-genre">' . $row['movieGenre'] . '</td>';
                                            echo '<td class="movie-duration">' . $row['movieDuration'] . ' mins</td>';
                                            echo '<td>' . $row['movieDirector'] . '</td>';
                                            echo '<td>' . $row['movieActors'] . '</td>';
                                            echo '<td class="movie-description">' . substr($row['movieDescription'], 0, 50) . '...</td>';
                                            echo '<td><a href="' . $row['movieTrailerLink'] . '" target="_blank" style = "color:blue; text-decoration: underline; ">Watch</a></td>';
                                            echo '<td>' . $row['movieRelDate'] . '</td>';
                                            echo '<td class="movie-actions">';

                                            if ($_SESSION['isSuperAdmin'] ?? false) {
                                                echo '<a href="editMovie.php?id=' . $row['movieID'] . '"><i class="fas fa-edit" title="Edit movie" style="margin-right:10px;color:#4CAF50;"></i></a>';
                                                echo '<a href="deleteMovie.php?id=' . $row['movieID'] . '" onclick="return confirm(\'Delete ' . htmlspecialchars($row['movieTitle']) . '? This cannot be undone!\')">';
                                                echo '<i class="fas fa-trash" title="Delete movie"></i></a>';
                                            } else {
                                                echo '<span class="disabled-action"><i class="fas fa-edit" title="Edit (Super Admin only)" style="margin-right:10px;color:#cccccc;"></i></span>';
                                                echo '<span class="disabled-action"><i class="fas fa-trash" title="Delete (Super Admin only)"></i></span>';
                                            }

                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="8" class="no-annot">No movies found</td></tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>