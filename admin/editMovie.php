<?php
require_once 'auth_check.php';
$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);

// Get movie details
$movie = [];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($link, "SELECT * FROM movietable WHERE movieID = $id");
    $movie = mysqli_fetch_assoc($result);
}

// Handle form submission
if (isset($_POST['submit'])) {
    $id = $_POST['movieID'];
    $title = $_POST['movieTitle'];
    $genre = $_POST['movieGenre'];
    $duration = $_POST['movieDuration'];
    $relDate = $_POST['movieRelDate'];
    $director = $_POST['movieDirector'];
    $actors = $_POST['movieActors'];

    // Handle file upload if new image provided
    $imgPath = $movie['movieImg']; // Keep current image by default
    if (!empty($_FILES["movieImg"]["name"])) {
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
            if (move_uploaded_file($_FILES["movieImg"]["tmp_name"], $targetFile)) {
                $imgPath = "img/" . $fileName;
            } else {
                echo '<p class="admin-error">Error uploading file.</p>';
            }
        }
    }

    // Update movie in database
    $stmt = $link->prepare("UPDATE movietable SET 
        movieImg = ?, 
        movieTitle = ?, 
        movieGenre = ?, 
        movieDuration = ?, 
        movieRelDate = ?, 
        movieDirector = ?, 
        movieActors = ?,
        movieDescription = ?,
        movieTrailerLink = ?
        WHERE movieID = ?");

    $description = $_POST['movieDescription'];
    $trailerLink = $_POST['movieTrailerLink'];

    $stmt->bind_param(
        "sssisssssi",
        $imgPath,
        $title,
        $genre,
        $duration,
        $relDate,
        $director,
        $actors,
        $description,
        $trailerLink,
        $id
    );

    if ($stmt->execute()) {
        echo '<p class="admin-success">Movie updated successfully!</p>';
        header("Refresh:2; url=movies.php");
    } else {
        echo '<p class="admin-error">Error: ' . $stmt->error . '</p>';
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="../style/alert_styles.css">

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
            <div class="admin-section-column">
                <div class="admin-section-panel">
                    <div class="admin-panel-section-header">
                        <h2>Edit Movie</h2>
                        <i class="fas fa-film" style="background-color: #4547cf"></i>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="movieID" value="<?= $movie['movieID'] ?>">
                        <input placeholder="Title" type="text" name="movieTitle" value="<?= htmlspecialchars($movie['movieTitle']) ?>" required>
                        <input placeholder="Genre" type="text" name="movieGenre" value="<?= htmlspecialchars($movie['movieGenre']) ?>" required>
                        <input placeholder="Duration (minutes)" type="number" name="movieDuration" value="<?= $movie['movieDuration'] ?>" required>
                        <input placeholder="Release Date" type="date" name="movieRelDate" value="<?= $movie['movieRelDate'] ?>" required>
                        <input placeholder="Director" type="text" name="movieDirector" value="<?= htmlspecialchars($movie['movieDirector']) ?>" required>
                        <input placeholder="Actors" type="text" name="movieActors" value="<?= htmlspecialchars($movie['movieActors']) ?>" required>
                        <textarea placeholder="Movie Description" name="movieDescription" required><?= htmlspecialchars($movie['movieDescription']) ?></textarea>
                        <input placeholder="Trailer Link (YouTube URL)" type="url" name="movieTrailerLink" value="<?= htmlspecialchars($movie['movieTrailerLink']) ?>" required>
                        <div class="current-image">
                            <p>Current Poster:</p>
                            <img src="../<?= $movie['movieImg'] ?>" style="max-width: 200px; margin: 10px 0;">
                        </div>
                        <input type="file" name="movieImg" accept="image/*">
                        <button type="submit" name="submit" class="form-btn">Update Movie</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>