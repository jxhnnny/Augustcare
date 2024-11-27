<?php
session_start();
require_once 'connection.php'; // Include database connection


if (isset($_POST['login'])) {
    $username = isset($_POST['userN']) ? $_POST['userN'] : ''; // Assign an empty string if not set
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Continue with your login logic

    // SQL query to fetch user data
    $sql = "SELECT ID, Username, Password FROM nannytbl WHERE Username = :username";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if ($result) {
        echo "User found.<br>"; // Debug
        if (password_verify($password, $result->Password)) {
            $_SESSION['userid'] = $result->ID;
            $_SESSION['username'] = $result->Username;
        } else {
            echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
        }
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Log in</title>
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

    <!--Login wrapper-->
    <div class="container">
        <div class="Login-Signup">
            <form action="" method="POST">
                <h2 class="title"> Log-in </h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" placeholder="Username" name="userN" pattern="[A-Za-z]+" title="Only letters are allowed">
                    <span class="error-message" style="color: red; display: none;">Only letters are allowed!</span>

                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" placeholder="Password" name="password">
                    <span class="error-message" style="color: red; display: none;">Only letters are allowed!</span>

                </div>


                <p>By logging in, you agree to our Terms of Use. See our Privacy Policy.</p>
                <button type="submit" name="login">Login</button>

            </form>

            <script src="scripts.js"></script>
</body>

</html>