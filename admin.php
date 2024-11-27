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

    .button-container button {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
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

    .button-container button:hover {
        background-color: #45a049;
    }


    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "augustcare"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function displayTable($conn, $tableName, $columns, $caption)
{
    // Generate SQL query
    $columnNames = implode(', ', $columns);
    $sql = "SELECT $columnNames FROM $tableName";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table border="1" cellpadding="10" cellspacing="0">';
        echo "<caption><h2>$caption</h2></caption>";
        echo '<tr>';
        foreach ($columns as $column) {
            echo "<th>" . ucfirst($column) . "</th>";
        }
        echo '<th>Actions</th>'; // Add actions column
        echo '</tr>';

        // Display data with action buttons
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            foreach ($columns as $column) {
                echo '<td>' . htmlspecialchars($row[$column]) . '</td>';
            }
            echo '<td>
                    <a href="edit.php?id=' . $row['ID'] . '&table=' . $tableName . '">
                        <button style="background-color: #4CAF50; color: white;">Edit</button>
                    </a>
                    <a href="delete.php?id=' . $row['ID'] . '&table=' . $tableName . '" onclick="return confirm(\'Are you sure you want to delete this record?\');">
                        <button style="background-color: red; color: white;">Delete</button>
                    </a>
                  </td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "No data found in $tableName.";
    }
}
// Ensure the connection is established
if (!isset($conn)) {
    die("Database connection failed. Please check connection.php.");
}

// SQL query to retrieve all data except the password
$sql = "SELECT ID, Fullname, Username, Email, PhoneNumber, Age, Gender, DomesticWork, Price, GegDate FROM nannytbl";
$result = $conn->query($sql);

// Check if records are found
if ($result->num_rows > 0) {

    echo '<table border="1" cellpadding="10" cellspacing="0">';
    echo '<caption><h2>Nannies</h2></caption>';
    echo '<tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Domestic Work</th>
            <th>Price</th>
            <th>Date Added</th>
          </tr>';

    // Loop through and display each row
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['ID']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Fullname']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Username']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Email']) . '</td>';
        echo '<td>' . htmlspecialchars($row['PhoneNumber']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Age']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Gender']) . '</td>';
        echo '<td>' . htmlspecialchars($row['DomesticWork']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Price']) . '</td>';
        echo '<td>' . htmlspecialchars($row['GegDate']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo 'No data found.';
}
// Ensure $conn is defined
if (!isset($conn)) {
    die("Database connection failed. Please check connection.php.");
}

// Query to retrieve all columns except Password
$sql = "SELECT ID, FullName, Username, email, PhoneNumber, Age, Gender, domesticWork, Price FROM cleaner";
$result = $conn->query($sql); // For MySQLi

if ($result->num_rows > 0) {
    echo '<table border="1" cellpadding="10" cellspacing="0">';
    echo '<caption><h2>Cleaners</h2></caption>';
    echo '<tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Domestic Work</th>
            <th>Price</th>
          </tr>';

    // Loop through each row and display data
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['ID']) . '</td>';
        echo '<td>' . htmlspecialchars($row['FullName']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Username']) . '</td>';
        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
        echo '<td>' . htmlspecialchars($row['PhoneNumber']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Age']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Gender']) . '</td>';
        echo '<td>' . htmlspecialchars($row['domesticWork']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Price']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo 'No data found.';
}
// Ensure $conn is defined
if (!isset($conn)) {
    die("Database connection failed. Please check connection.php.");
}

// Query to retrieve all data except the password
$sql = "SELECT ID, Fullname, Username, Email, PhoneNumber, RegDate FROM parentstbl";
$result = $conn->query($sql); // Execute the query

if ($result->num_rows > 0) {
    echo '<table border="1" cellpadding="10" cellspacing="0">';
    echo '<caption><h2>Parents</h2></caption>';
    echo '<tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Registration Date</th>
          </tr>';

    // Loop through and output data
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['ID']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Fullname']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Username']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Email']) . '</td>';
        echo '<td>' . htmlspecialchars($row['PhoneNumber']) . '</td>';
        echo '<td>' . htmlspecialchars($row['RegDate']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo 'No data found.';
}
// Ensure the database connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to retrieve data from the messagetbl table
$sql = "SELECT ID, Name, Email, message, date FROM messagetbl";
$result = $conn->query($sql); // For MySQLi

if ($result->num_rows > 0) {
    echo '<table border="1" cellpadding="10" cellspacing="0">';
    echo '<caption><h2>Messages</h2></caption>';
    echo '<tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Date</th>
          </tr>';

    // Fetch and display each row of data
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['ID']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['Email']) . '</td>';
        echo '<td>' . htmlspecialchars($row['message']) . '</td>';
        echo '<td>' . htmlspecialchars($row['date']) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo 'No data found.';
}


// Close the connection
$conn->close();

echo '<button onclick="location.href=\'logout.php\';">logout</button>';
