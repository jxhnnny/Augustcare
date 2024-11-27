<?php
// nanny_notifications.php - Add this to the nanny's dashboard
require_once 'connection.php';

// Ensure nanny is logged in
//session_start();
//if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'nanny') {
//    header('Location: login.php');
//   exit();
//}

// Handle request responses
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notification_id'])) {
    $notification_id = $_POST['notification_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE notifications SET status = ?, is_read = TRUE WHERE id = ?");
    $stmt->bind_param("si", $status, $notification_id);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notifications</title>
    <style>
        .notification-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            background-color: #fff;
        }

        .notification-card.unread {
            background-color: #f0f7ff;
            border-left: 4px solid #1e88e5;
        }

        .notification-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-accept {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-reject {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-pending {
            background-color: #ffd700;
            color: black;
        }

        .status-accepted {
            background-color: #4CAF50;
            color: white;
        }

        .status-rejected {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>

<body>
    <h2>My Notifications</h2>

    <?php
    // Fetch notifications for the logged-in nanny
    $nanny_email = $_SESSION['email'];
    $query = "SELECT * FROM notifications WHERE nanny_email = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nanny_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $unreadClass = $row['is_read'] ? '' : 'unread';
    ?>
            <div class="notification-card <?php echo $unreadClass; ?>">
                <h4><?php echo htmlspecialchars($row['parent_name']); ?> sent you a request</h4>
                <p><?php echo htmlspecialchars($row['message']); ?></p>
                <p>Sent on: <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></p>

                <?php if ($row['status'] === 'pending') { ?>
                    <div class="notification-actions">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="notification_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="status" value="accepted">
                            <button type="submit" class="btn-accept">Accept</button>
                        </form>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="notification_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn-reject">Reject</button>
                        </form>
                    </div>
                <?php } else { ?>
                    <span class="status-badge status-<?php echo $row['status']; ?>">
                        <?php echo ucfirst($row['status']); ?>
                    </span>
                <?php } ?>
            </div>
    <?php
        }
    } else {
        echo '<p>No notifications yet.</p>';
    }
    ?>

    <script>
        // Mark notifications as read when viewed
        window.onload = async function() {
            try {
                await fetch('mark_notifications_read.php', {
                    method: 'POST'
                });
            } catch (error) {
                console.error('Error marking notifications as read:', error);
            }
        }
    </script>
</body>

</html>