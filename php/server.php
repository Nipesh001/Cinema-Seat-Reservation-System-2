<?php
header('Content-Type: application/json');

$link = null;
$response = ['status' => 'error', 'message' => ''];

try {
    if (isset($_POST['fName'], $_POST['lName'], $_POST['eMail'], $_POST['feedback'])) {
        // Database connection
        $link = new mysqli(
            "localhost",
            "root",
            "",
            "cinema_db",
            3307
        );

        if ($link->connect_error) {
            throw new Exception("Connection failed: " . $link->connect_error);
        }

        // Prepare and execute query
        $stmt = $link->prepare("INSERT INTO `feedbacktable` ( `senderfName`, `senderlName`, `sendereMail`, `senderfeedback`) VALUES (?, ?, ?, ?)");

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $link->error);
        }


        $fName = trim($_POST['fName'] ?? '');
        $lName = trim($_POST['lName'] ?? '');
        $eMail = trim($_POST['eMail'] ?? '');
        $feedback = trim($_POST['feedback'] ?? '');

        $stmt->bind_param("ssss", $fName, $lName, $eMail, $feedback);


        if ($stmt->execute()) {
            $response = [
                'status' => 'success',
                'message' => 'Message sent successfully!',
            ];
        } else {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $stmt->close();
    } else {
        $response['message'] = 'All fields are required';
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
    error_log($e->getMessage());
}



if ($link instanceof mysqli) {
    $link->close();
}

echo json_encode($response);
