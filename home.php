<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <!--online link for fonts (stickers)-->
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('images/dcare.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body>
    <!--navigation-->
    <?php include 'navigation.php'; ?>

    <!--Home page-->
    <section id="Home">
        <h2> Details written</h2>
        <p>More details</p>
        <!--creating buttons-->
        <div class="button">
            <a class="pink" href="Sign up.php">Sign up</a>
            <a href="Sign up.php">login</a>
        </div>


        <!--Home page infor -->
        <section id="features">
            <h1> You know what's best for you - we just make it easier</h1>
            <p> edits</p>

            <div class="fea-base">
                <div class="fea-box">
                    <i class="fa-solid fa-baby-carriage"></i>
                    <h3>We take care of your safety </h3>
                </div>
                <div class="fea-box">
                    <i class="fa-solid fa-baby"></i>
                    <h3>We just make it easier </h3>
                </div>
                <div class="fea-box">
                    <i class="fa-solid fa-person-breastfeeding"></i>
                    <h3>Less worry ,more peace of mind </h3>
                </div>
            </div>
        </section>
    </section>

    <!--Profiles-->


    <?php include 'footer.php'; ?>



</body>

</html>