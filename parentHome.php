<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "augustcare";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Assuming user is logged in and we have their session details
session_start();
$parent_id = $_SESSION['user_id'] ?? 0;
$parent_name = $_SESSION['user_name'] ?? 'Parent';
$parent_email = $_SESSION['user_email'] ?? '';
?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendServiceRequestEmail($parent_name, $parent_email, $nanny_email, $message) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Update with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com'; // Update with your email
        $mail->Password = 'your_password'; // Update with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom($parent_email, $parent_name);
        $mail->addAddress($nanny_email);
        $mail->addReplyTo($parent_email, $parent_name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Service Request from ' . $parent_name;
        $mail->Body = "
            <html>
            <body>
                <h2>Service Request</h2>
                <p>You have received a new service request from <strong>" . htmlspecialchars($parent_name) . "</strong>.</p>
                <p><strong>Parent's Email:</strong> " . htmlspecialchars($parent_email) . "</p>
                <p><strong>Message:</strong></p>
                <p>" . nl2br(htmlspecialchars($message)) . "</p>
                <p>Please respond directly to the parent's email.</p>
                <p>Best regards,<br>AugustCare Team</p>
            </body>
            </html>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

// Handle service request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $nanny_id = filter_input(INPUT_POST, 'nanny_id', FILTER_VALIDATE_INT);
    $nanny_email = filter_input(INPUT_POST, 'nanny_email', FILTER_VALIDATE_EMAIL);
    $message = trim($_POST['message']);

    // Validate inputs
    if (!$parent_id || !$parent_name || !$parent_email || !$nanny_id || !$nanny_email || empty($message)) {
        http_response_code(400);
        echo json_encode([
            'success' => false, 
            'message' => 'Invalid input data. Please ensure you are logged in and all fields are filled.'
        ]);
        exit();
    }

    // Attempt to send email
    if (sendServiceRequestEmail($parent_name, $parent_email, $nanny_email, $message)) {
        http_response_code(200);
        echo json_encode([
            'success' => true, 
            'message' => 'Service request sent successfully!'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to send service request. Please try again later.'
        ]);
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .button-container {
            text-align: center;
            margin: 20px 0;
        }

        .button-container button, .request-btn {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #EEA9BA;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }

        .button-container button:hover, .request-btn:hover {
            background-color: #D1D0D0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
        }

        th {
            background: rgb(225, 171, 171);
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        td {
            color: #555;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #alertBox {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            display: none;
            z-index: 1000;
        }

        #alertBox.error {
            background-color: #f44336;
        }

        @media (max-width: 600px) {
            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Alert Box -->
    <div id="alertBox"></div>

    <h2>Available Nannies</h2>
    <div class="button-container">
        <button onclick="location.href='search.php';">Search For Nanny</button>
        <button onclick="location.href='services.php';">Our Services</button>
    </div>
    <table>
        <tr>
            <th>Fullname</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Domestic Work</th>
            <th>Price</th>
            <th>Date Added</th>
            <th>Action</th>
        </tr>
        <?php
        $query = "SELECT id, Fullname, Email, PhoneNumber, Age, Gender, DomesticWork, Price, GegDate FROM nannytbl";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                foreach (['Fullname', 'Email', 'PhoneNumber', 'Age', 'Gender', 'DomesticWork', 'Price', 'GegDate'] as $column) {
                    echo '<td>' . htmlspecialchars($row[$column]) . '</td>';
                }
                // Add request button
                echo '<td><button class="request-btn" onclick="openRequestModal(' . 
                    htmlspecialchars(json_encode($row['id'])) . ', ' . 
                    htmlspecialchars(json_encode($row['Email'])) . ', ' . 
                    htmlspecialchars(json_encode($row['Fullname'])) . ')">Request Service</button></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="9">No records found.</td></tr>';
        }
        ?>
    </table>

    <!-- Request Service Modal -->
    <div id="requestModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Request Nanny Service</h2>
            <form id="serviceRequestForm">
                <input type="hidden" id="nanny_id" name="nanny_id">
                <input type="hidden" id="nanny_email" name="nanny_email">
                
                <label for="message">Your Request:</label><br>
                <textarea id="message" name="message" rows="4" style="width: 100%; margin-bottom: 10px;" placeholder="Please describe the service you need..." required></textarea>
                
                <button type="submit" style="width: 100%; padding: 10px; background-color: #EEA9BA; color: white; border: none; border-radius: 4px; cursor: pointer;">Send Request</button>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        var modal = document.getElementById('requestModal');
        var span = document.getElementsByClassName('close')[0];
        var alertBox = document.getElementById('alertBox');

        function openRequestModal(nannyId, nannyEmail, nannyName) {
            document.getElementById('nanny_id').value = nannyId;
            document.getElementById('nanny_email').value = nannyEmail;
            modal.style.display = 'block';
        }

        // Close the modal when clicking on <span> (x)
        span.onclick = function() {
            modal.style.display = 'none';
        }

        // Close the modal when clicking anywhere outside of it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Handle form submission via AJAX
        document.getElementById('serviceRequestForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Prepare form data
            var formData = new FormData(this);

            // Send AJAX request
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Show alert
                alertBox.textContent = data.message;
                alertBox.style.display = 'block';
                
                if (data.success) {
                    alertBox.classList.remove('error');
                    // Close modal after successful submission
                    setTimeout(() => {
                        modal.style.display = 'none';
                        alertBox.style.display = 'none';
                    }, 3000);
                } else {
                    alertBox.classList.add('error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alertBox.textContent = 'An unexpected error occurred.';
                alertBox.style.display = 'block';
                alertBox.classList.add('error');
            });
        });
    </script>

    <div class="button-container">
        <button onclick="location.href='logout.php';">Logout</button>
    </div>
</body>
</html>
<?php
$conn->close();
?>