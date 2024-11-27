<?php
// Include the connection file
include 'connection.php';

// Query to fetch all records from the 'searchnanny' table
try {
    // Prepare and execute the query
    $stmt = $dbh->prepare("SELECT * FROM searchnanny");
    $stmt->execute();

    // Fetch all results as an associative array
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Nanny Table</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .button-container1 button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
        }

        th {
            background: rgb(225, 171, 171);
            /* Green */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
            /* Light grey for even rows */
        }

        tr:hover {
            background-color: #ddd;
            /* Light grey on hover */
        }

        td {
            color: #555;
            /* Darker text for better readability */
        }

        @media (max-width: 600px) {
            table {
                font-size: 14px;
                /* Smaller font on small screens */
            }
        }
    </style>
</head>

<body>

    <h2>Available Jobs</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Location</th>
            <th>Number of Children</th>
            <th>Datetime</th>
            <th>Age Category</th>
            <th>Language</th>
            <th>Domestic Work</th>
            <th>Price</th>
        </tr>

        <?php
        // Check if there are any records
        if ($results) {
            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['location']) . '</td>';
                echo '<td>' . htmlspecialchars($row['numberOfchildren']) . '</td>';
                echo '<td>' . htmlspecialchars($row['datetime']) . '</td>';
                echo '<td>' . htmlspecialchars($row['ageCategory']) . '</td>';
                echo '<td>' . htmlspecialchars($row['language']) . '</td>';
                echo '<td>' . htmlspecialchars($row['domesticWork']) . '</td>';
                echo '<td>' . htmlspecialchars($row['price']) . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="8">No records found.</td></tr>';
        }
        ?>

    </table>

</body>
<div class="button-container1">
    <button onclick="location.href='logout.php';">logout</button>
</div>

</html>