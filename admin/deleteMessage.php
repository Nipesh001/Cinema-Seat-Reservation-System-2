<?php
require_once 'auth_check.php';
$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM feedbacktable WHERE msgID = $id";
    if (mysqli_query($link, $query)) {
        echo "<script>alert('Message deleted successfully!'); window.location.href='messages.php';</script>";
    } else {
        echo "<script>alert('Error deleting message.'); window.location.href='messages.php';</script>";
    }
} else {
    header("Location: messages.php");
}
