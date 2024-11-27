<?php include 'navigation.php';
include 'connection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Validate input
    if (!empty($name) && !empty($email) && !empty($message)) {
        try {
            // Prepare SQL query
            $stmt = $dbh->prepare("INSERT INTO messagetbl (Name, Email, message) VALUES (:name, :email, :message)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);

            // Execute query
            if ($stmt->execute()) {

                header("Location: logout.php");
                echo "<script>alert('You have successfully registered with us');</script>";
            } else {
                echo "Failed to send the message.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "All fields are required.";
    }
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <!--online link for fonts (stickers)-->
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
    <link rel="stylesheet" href="style.css">


</head>

<body>
    <!--navigation-->


    <!--Contact us infor-->
    <section class="contact">
        <div class="container">
            <h2> Contact Us<h2>
                    <div class="contact-wrapper">
                        <div class="contact-form">
                            <h3> Send us a message</h3>
                            <form method="POST">
                                <div class="form-group">
                                    <input type="text" name="name" placeholder="Your Name">
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" placeholder="Your email">
                                </div>
                                <div class="form-group">
                                    <textarea name="message" placeholder="Your Message"> </textarea>
                                </div>
                                <button type="submit"> Send Message</button>
                            </form>
                        </div>
                        <div class="contact-info">
                            <h3> Contact Information</h3>
                            <p> <i class="fas fa-phone"></i> +264812502755 </p>
                            <p> <i class="fas fa-envelope"></i> augustcare03@gmail.com </p>
                            <p> <i class="fas fa-map-marker-alt"></i> 123 street, whk, namibia </p>
                        </div>
                    </div>
    </section>

    <?php include 'footer.php'; ?>



</body>

</html>