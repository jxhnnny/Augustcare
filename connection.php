<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "augustcare";

try {
    $dbh = new PDO("mysql:host=localhost;dbname=augustcare", "root", "");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {

    echo "Database connection failed: " . $e->getMessage();
    exit();
}
