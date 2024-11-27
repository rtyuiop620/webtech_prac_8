<?php 
include ("../includes/session.php"); 
include ("../includes/db_connection.php"); 
include ("../includes/functions.php"); 
confirm_logged_in(); 

// Test the database connection
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Destinations</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <div class="container">
        <h1>Manage Destinations</h1>
        <a href="new_destination.php">Add New Destination</a>
        <h2>Destination List</h2>
        
        <?php
        // Query to fetch destinations
        $query = "SELECT * FROM destinations ORDER BY name ASC";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            echo "<p style='color: red;'>Error fetching destinations: " . mysqli_error($connection) . "</p>";
        } else {
            // Display destinations if query was successful
            while ($destination = mysqli_fetch_assoc($result)) {
                echo "<p>" . htmlspecialchars($destination['name']) . " - <a href=\"edit_destination.php?id=" . urlencode($destination['id']) . "\">Edit</a></p>";
            }
        }
        ?>
    </div>
</body>
</html>