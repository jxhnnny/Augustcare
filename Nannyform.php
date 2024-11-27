<?php
session_start();
require_once 'connection.php'; // Ensure this initializes the $dbh variable (PDO connection)

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Get form data and sanitize inputs
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $age = intval($_POST['age']);
    $gender = isset($_POST['choice']) ? $_POST['choice'] : '';
    $domesticWork = $_POST['domestickWorker'];
    $price = floatval($_POST['price']);

    // Validation
    $errors = array();

    // Validate empty fields
    if (
        empty($fullname) || empty($username) || empty($email) || empty($phone) ||
        empty($password) || empty($confirmPassword) || empty($age) ||
        empty($gender) || empty($domesticWork) || empty($price)
    ) {
        $errors[] = "All fields are required";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Validate password match
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }

    // Validate password length
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }

    // Validate age
    if ($age < 18 || $age > 100) {
        $errors[] = "Age must be between 18 and 100";
    }

    // Validate price
    if ($price <= 0) {
        $errors[] = "Price must be greater than 0";
    }

    // Check if username already exists
    $stmt = $dbh->prepare("SELECT id FROM nannytbl WHERE Username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $errors[] = "Username already exists";
    }
    $stmt->closeCursor(); // Close the statement cursor

    // Check if email already exists
    $stmt = $dbh->prepare("SELECT id FROM nannytbl WHERE Email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $errors[] = "Email already exists";
    }
    $stmt->closeCursor(); // Close the statement cursor

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind
        $stmt = $dbh->prepare(
            "INSERT INTO nannytbl (Fullname, Username, Email, PhoneNumber, Password, Age, Gender, DomesticWork, Price)
            VALUES (:fullname, :username, :email, :phone, :password, :age, :gender, :domesticWork, :price)"
        );

        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindParam(':domesticWork', $domesticWork, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            echo  "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
        } else {
            echo "<script>
                alert('Error occurred during registration. Please try again.');
                window.location.href = 'register.php';
            </script>";
        }
    } else {
        // Display errors
        echo "<script>alert('" . implode("\\n", $errors) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nanny Form</title>
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


    <section id="Nannyform" class="container">
        <form action="" method="POST">
            <h2>Nanny Registration</h2>
            <div class="form">
                <div class="input-box row">
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
                    <input type="email" placeholder="Email" id="email" name="email" required>
                    <span id="error-message" style="color:red;"></span>

                </div>
                <div class="input-box">
                    <label for="name">Phone Number</label>
                    <input type="text" placeholder="Phone Number" name="phone" required maxlength="10" pattern="[0-9]+">
                </div>
                <div class="input-box">
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="Password" name="password" require minlength="6">
                </div>
                <div class="input-box">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" placeholder="Confirm Password" name="confirmPassword" required>
                </div>
                <div class="input-box">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" required min="18" max="100">
                </div>
                <div class="gender-title">Gender</div>
                <div class="gender-category">
                    <input type="radio" name="choice" id="male" value="Male">
                    <label for="gender">Male</label>
                    <input type="radio" name="choice" id="Female" value="Female">
                    <label for="gender">Female</label>
                    <input type="radio" name="choice" id="other" value="Others">
                    <label for="gender">Other</label>
                </div>


                <div class="Domestic work-title">Domestic work</div>
                <select name="domestickWorker" id="options">
                    <option value="">Select an option</option>
                    <option value="Cleaning">Cleaning</option>
                    <option value="Ironing">Ironing</option>
                    <option value="Washing">Washing</option>
                    <option value="Other">Other</option>
                </select>
                <div class="input-box">
                    <label for="name">Price</label>
                    <input type="number" placeholder="Price" name="price" maxlength="2" required>
                </div>

                <div class="alert">
                    <p>By clicking Sign up,you agree to our Terms and Privacy policy.</p>
                    <label>Already have account?</label><a href="nannylogin.php">signin</a>
                </div>
                <div class="row">
                    <button type="submit" name="submit"> SignUp </button>
                </div>
        </form>
        </div>
        </div>
        </form>
    </section>
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