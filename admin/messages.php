<?php
require_once 'auth_check.php';
$link = mysqli_connect("localhost", "root", "", "cinema_db", 3307);
$messagesNo = 0;
$tableExists = mysqli_query($link, "SHOW TABLES LIKE 'feedbackTable'");
if (mysqli_num_rows($tableExists) > 0) {
    $messagesNo = mysqli_num_rows(mysqli_query($link, "SELECT * FROM feedbackTable"));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Messages</title>
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
                    <i class="fas fa-envelope" style="background-color: #3cbb6c"></i>
                    <h2 style="color: #3cbb6c"><?= $messagesNo ?></h2>
                    <h3>Messages</h3>
                </div>
            </div>
            <div class="admin-section-panel">
                <div class="admin-panel-section-header">
                    <h2>Customer Messages</h2>
                    <i class="fas fa-envelope" style="background-color: #3cbb6c"></i>
                </div>
                <div class="admin-panel-section-content">
                    <table class="messages-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM feedbackTable ORDER BY msgID DESC";
                            $result = mysqli_query($link, $sql);

                            if ($result) {
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_array($result)) {
                                        $timestamp = isset($row['msgDate']) ? date('M j, Y g:i a', strtotime($row['msgDate'])) : '';
                                        echo '<tr>';
                                        echo '<td>' . (isset($row['senderfName']) ? htmlspecialchars($row['senderfName'] . ' ' . $row['senderlName']) : '') . '<br><small>' . $timestamp . '</small></td>';
                                        echo '<td><a href="mailto:' . (isset($row['sendereMail']) ? htmlspecialchars($row['sendereMail']) : '') . '">' . (isset($row['sendereMail']) ? htmlspecialchars($row['sendereMail']) : '') . '</a></td>';
                                        echo '<td class="message-content">' . (isset($row['senderfeedback']) ? nl2br(htmlspecialchars($row['senderfeedback'])) : '') . '</td>';
                                        echo '<td class="message-actions">';
                                        if (isset($row['sendereMail'])) {
                                            $subject = "Re: Your message to PREMIUM CINEMA";
                                            $body = "Dear " . $row['senderfName'] . ",\n\n";
                                            $body .= "Thank you for your message:\n\n";
                                            $body .= "> " . str_replace("\n", "\n> ", $row['senderfeedback']) . "\n\n";
                                            $body .= "We appreciate your feedback and will respond shortly.\n\n";
                                            $body .= "Best regards,\nPREMIUM CINEMA Team";
                                            echo '<a href="mailto:' . htmlspecialchars($row['sendereMail']) . '?subject=' . rawurlencode($subject) . '&body=' . rawurlencode($body) . '" 
                                                 onclick="return confirm(\'Are you sure you want to reply to this message?\')">
                                                 <i class="fas fa-reply" title="Reply to ' . htmlspecialchars($row['senderfName']) . '"></i></a>';
                                        }
                                        if (isset($row['msgID'])) {
                                            echo '<a href="deleteMessage.php?id=' . htmlspecialchars($row['msgID']) . '" 
                                                 onclick="return confirm(\'Are you sure you want to delete this message?\')">
                                                 <i class="fas fa-trash" title="Delete message"></i></a>';
                                        }
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="no-annot">No messages found</td></tr>';
                                }
                                mysqli_free_result($result);
                            } else {
                                echo '<tr><td colspan="5" class="no-annot">Database query failed</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Add tooltips for better UX
        document.querySelectorAll('[title]').forEach(el => {
            el.addEventListener('mouseover', function(e) {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip';
                tooltip.textContent = this.getAttribute('title');
                document.body.appendChild(tooltip);

                const rect = this.getBoundingClientRect();
                tooltip.style.left = (rect.left + window.scrollX) + 'px';
                tooltip.style.top = (rect.bottom + window.scrollY + 5) + 'px';

                this.addEventListener('mouseout', function() {
                    tooltip.remove();
                }, {
                    once: true
                });
            });
        });
    </script>
</body>

</html>