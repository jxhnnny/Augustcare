<?php
session_start();
include 'connection.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $location = $_POST['Location'];
    $numberOfchildren = $_POST['numberOfchildren'];
    $datetime = $_POST['datetime'];
    $ageCategory = $_POST['choice'];
    $language = $_POST['language'];
    $domesticWork = isset($_POST['Domestic']) ? implode(', ', $_POST['Domestic']) : ''; // For multiple checkboxes
    $price = $_POST['price'];

    try {
        // Prepare the SQL query
        $stmt = $dbh->prepare("INSERT INTO searchnanny (location, numberOfchildren, datetime, ageCategory, language, domesticWork, price) 
                               VALUES (:location, :numberOfchildren, :datetime, :ageCategory, :language, :domesticWork, :price)");

        // Bind parameters
        $stmt->bindParam(':location', $location, PDO::PARAM_STR);
        $stmt->bindParam(':numberOfchildren', $numberOfchildren, PDO::PARAM_INT);
        $stmt->bindParam(':datetime', $datetime, PDO::PARAM_STR);
        $stmt->bindParam(':ageCategory', $ageCategory, PDO::PARAM_STR);
        $stmt->bindParam(':language', $language, PDO::PARAM_STR);
        $stmt->bindParam(':domesticWork', $domesticWork, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('You have successfully registered with us');</script>";
            header("Location: home.php");
            exit();
        } else {
            echo "<script>alert('Error occurred, please try again');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>search</title>
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

    <section id="Parent-Details" class="container">
        <form action="" method="POST">
            <h2>Search For Nanny</h2>
            <div class="form">
                <div class="input-box">

                    <label for="cars">Choose a loaction:</label>
                    <select name="Location">
                        <option value=" Katutura">Katutura</option>
                        <option value="Windhoek west/north">Windhoek west/north</option>
                        <option value="Otjomuise">Otjomuise</option>
                        <option value="Khomasdal">Khomasdal</option>
                        <option value="Central City">Central City</option>
                        <option value="Upper Town">Upper Town</option>
                    </select>
                </div>
                <br>
                <div class="input-box">
                    <label for="name">Number of children</label>
                    <input type="text" placeholder="" name="numberOfchildren" required>
                </div><br>

                <div class="input-box">
                    <label for="datetime">Select time and date for Nanny's presents:</label>
                    <input type="datetime-local" id="datetime" name="datetime" required>
                    <span id="error-message" style="color:red;"></span>
                </div>
                <br> <br>
                <div class="gender-title">Age</div>
                <div class="Age-category">
                    <label for="gender">Baby</label>
                    <input type="radio" name="choice" id="male" value="Baby">
                    <label for="gender">Toddler</label>
                    <input type="radio" name="choice" id="Female" value="Toddler">
                    <label for="gender">Preschooler</label>
                    <input type="radio" name="choice" id="other" value="Preschooler">
                    <label for="gender">Teenager</label>
                    <input type="radio" name="choice" id="other" value="Teenager">
                    <label for="gender">All</label>
                    <input type="radio" name="choice" id="other" value="All">
                </div>
                <br>
                <div class="input-box">
                    <p>Which languages do you speak?</p>
                    <label for="name">Language</label>
                    <input type="text" placeholder="Language" name="language" required>
                </div>
                <div class="Domestic work-title">Domestic work</div>
                <div class="Domestic-category">
                    <label for="checkbox">Cleaning</label>
                    <input type="checkbox" name="Domestic[]" id="Baby" value="Cleaning">
                    <label for="gender">Ironing</label>
                    <input type="checkbox" name="Domestic[]" value="Ironing">
                    <label for="Domestic">Washing</label><br>
                    <input type="checkbox" name="Domestic[]" value="Washing">
                    <label for="Domestic">Others</label>
                    <input type="checkbox" name="Domestic[]" value="Others">

                </div><br>
                <div class="input-box">
                    <label for="name">Price</label>
                    <input type="number" placeholder="Price" name="price" required maxlength="4">
                </div>


            </div>
            </div>
            <div class="Parntbutton">
                <button type="submit" name="submit"> Submit </button>
            </div>
        </form>
</body>
<script>
    // Get today's date and time
    var today = new Date();

    // Format the date to YYYY-MM-DDTHH:mm
    var formattedDate = today.toISOString().slice(0, 16);

    // Set the min attribute of the datetime input
    document.getElementById('datetime').setAttribute('min', formattedDate);
</script>

</html>