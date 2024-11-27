<?php
session_start();
include 'connection.php'; // Assuming this file contains the PDO connection setup

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate input
    if ($password !== $confirmPassword) {
        echo "Passwords do not match!";
        exit;
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind using PDO
    $stmt = $dbh->prepare("INSERT INTO parentstbl (fullname, username, email, PhoneNumber, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bindParam(1, $fullname, PDO::PARAM_STR);
    $stmt->bindParam(2, $username, PDO::PARAM_STR);
    $stmt->bindParam(3, $email, PDO::PARAM_STR);
    $stmt->bindParam(4, $phone, PDO::PARAM_STR);
    $stmt->bindParam(5, $hashedPassword, PDO::PARAM_STR);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('You have successfully registered with us');</script>";
        header("Location: parentHome.php");
        exit();
    } else {
        echo "Error: " . $stmt->errorInfo()[2]; // Use errorInfo() for PDO error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Form</title>
    <!--online link for fonts (stickers)-->
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* General body styling */
        body {
            margin: 0;
            background-color: hsl(0, 0%, 98%);
            color: #333;
            font-family: Arial, sans-serif;
        }

        /* Container styling */
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background: rgb(225, 171, 171);
            border-radius: 5px;
        }

        /* Form styling */
        form {
            display: grid;
            gap: 1rem;
        }

        /* Input fields and textareas */
        input[type=text],
        input[type=email],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Submit button styling */
        input[type=submit] {
            background-color: #04AA6D;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #45a049;
            /* Darker green on hover */
        }

        /* Responsive design */
        @media screen and (max-width: 600px) {
            .row {
                flex-direction: column;
                /* Stack elements vertically on small screens */
            }
        }
    </style>
</head>

<body>


    <section id="Parentform" class="container">
        <form action="" method="POST">
            <h2>Parent Registration</h2>
            <div class="form">
                <div class="input-box">
                    <label for="name">Full Name</label>
                    <input type="text" placeholder="Full Name" name="fullname" pattern="[A-Za-z]+" title="Only letters are allowed" required>
                    <span class="error-message" style="color: red; display: none;">Only letters are allowed!</span>
                </div>
                <div class="input-box">
                    <label for="name">Username</label>
                    <input type="text" placeholder="Username" name="username" pattern="[A-Za-z]+" title="Only letters are allowed" required>
                    <span class="error-message" style="color: red; display: none;">Only letters are allowed!</span>
                </div>
                <div class="input-box">
                    <label for="name">Email</label>
                    <input type="email" placeholder="Email" name="email" required>
                    <span id="error-message" style="color:red;"></span>
                </div>
                <div class="input-box">
                    <label for="name">Phone Number</label>
                    <input type="text" placeholder="Phone Number" name="phone" required maxlength="10" pattern="[0-9]+">
                </div>
                <div class="input-box">
                    <label for="name">Password</label>
                    <input type="text" placeholder="Password" name="password" required>
                </div>
                <div class="input-box">
                    <label for="name">Confirm Password</label>
                    <input type="text" placeholder="Confirm Passowrd" name="confirmPassword" required>
                </div>
            </div>
            <div class="alert">
                <p>By clicking Sign up,you agree to our Terms and Privacy policy.</p>
                <label>Already have account?</label><a href="parentlogin.php">signin</a>
            </div>


            <button type="submit" name="submit"> SignUp </button>
        </form>
    </section>

    <script src="scripts.js"></script>
</body>
<script>
    function validateEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }

    document.getElementById('signupForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form submission
        const emailInput = document.getElementById('email');
        const errorMessage = document.getElementById('error-message');

        if (validateEmail(emailInput.value)) {
            errorMessage.textContent = ''; // Clear any previous error message
            alert('Email is valid! Form submitted.');
            // Here you can proceed with form submission or further processing
        } else {
            errorMessage.textContent = 'Please enter a valid email address.';
        }
    });
</script>

</html>