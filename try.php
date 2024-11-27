<?php
// notifications_table.php - Run this once to create the notifications table
session_start();
include 'connection.php';



//$conn->query($createNotificationsTable);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Previous head content remains the same -->
    <style>
        /* Previous styles remain the same */
        .request-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            width: 400px;
        }

        .modal-content {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }

        .btn-secondary {
            background-color: #f44336;
            color: white;
        }

        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>

<body>
    <!-- Previous content remains the same until the table -->

    <table>
        <!-- Previous table headers remain the same -->
        <?php
        require_once 'connection.php';

        $query = "SELECT * FROM nannytbl";
        $stmt = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr class="clickable" 
                        data-email="' . htmlspecialchars($row['Email']) . '"
                        data-name="' . htmlspecialchars($row['Fullname']) . '"
                        data-id="' . htmlspecialchars($row['id']) . '">';
                // Previous table cells remain the same
                echo '</tr>';
            }
        }
        ?>
    </table>

    <!-- Updated Request Modal -->
    <div class="request-modal" id="requestModal">
        <div class="modal-content">
            <h3>Send Request to <span id="nannyName"></span></h3>
            <p>Send a hiring request to this nanny. They will be notified when they log in.</p>
            <textarea id="requestMessage" placeholder="Add a message (optional)" rows="4" style="width: 100%;"></textarea>
            <div class="btn-group">
                <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button class="btn btn-primary" onclick="sendRequest()">Send Request</button>
            </div>
            <div class="success-message" id="successMessage">
                Request sent successfully!
            </div>
        </div>
    </div>

    <script>
        let selectedNanny = null;

        document.querySelectorAll('tr.clickable').forEach(row => {
            row.addEventListener('click', () => {
                selectedNanny = {
                    id: row.dataset.id,
                    email: row.dataset.email,
                    name: row.dataset.name
                };
                openRequestModal();
            });
        });

        function openRequestModal() {
            const modal = document.getElementById('requestModal');
            document.getElementById('nannyName').textContent = selectedNanny.name;
            modal.style.display = 'block';
        }

        function closeModal() {
            document.getElementById('requestModal').style.display = 'none';
            document.getElementById('successMessage').style.display = 'none';
            document.getElementById('requestMessage').value = '';
        }

        async function sendRequest() {
            const message = document.getElementById('requestMessage').value;
            const parentId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '0'; ?>;
            const parentName = <?php echo isset($_SESSION['name']) ? "'" . $_SESSION['name'] . "'" : "'Unknown'"; ?>;

            try {
                const response = await fetch('send_request.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `parent_id=${parentId}&parent_name=${parentName}&nanny_id=${selectedNanny.id}&nanny_email=${selectedNanny.email}&message=${encodeURIComponent(message)}`
                });

                const result = await response.text();
                document.getElementById('successMessage').style.display = 'block';
                setTimeout(closeModal, 2000);
            } catch (error) {
                console.error('Error:', error);
                alert('Error sending request');
            }
        }
    </script>
</body>

</html>