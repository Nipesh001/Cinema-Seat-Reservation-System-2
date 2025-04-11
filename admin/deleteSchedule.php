<?php
require_once 'auth_check.php';
$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);

if(isset($_GET['id'])) {
    $scheduleID = $_GET['id'];
    
    // Verify table exists first
    $tableExists = mysqli_query($link, "SHOW TABLES LIKE 'scheduleTable'");
    if(mysqli_num_rows($tableExists) > 0) {
        $stmt = $link->prepare("DELETE FROM scheduleTable WHERE scheduleID = ?");
        $stmt->bind_param("i", $scheduleID);
        
        if($stmt->execute()) {
            echo '<script>alert("Schedule deleted successfully!");</script>';
        } else {
            echo '<script>alert("Error deleting schedule: ' . $stmt->error . '");</script>';
        }
        $stmt->close();
    } else {
        echo '<script>alert("Schedule table not found");</script>';
    }
}

// Redirect back to schedule page
echo '<script>window.location.href = "schedule.php";</script>';
?>
