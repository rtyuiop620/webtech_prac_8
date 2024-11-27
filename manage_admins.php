<?php
include("../includes/session.php");
include("../includes/db_connection.php");
include("../includes/functions.php");
confirm_logged_in();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Admins</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>

<body>
    <div class="container">
        <h1>Manage Admins</h1>
        <a href="new_admin.php">Add New Admin</a>
        <h2>Admin List</h2>
        <table border="1">
            <tr>
                <th>Username</th>
                <th>Actions</th>
            </tr>

            <?php
            // Retrieve all admins from the database
            $query = "SELECT * FROM admins ORDER BY username ASC";
            $result = mysqli_query($connection, $query);

            while ($admin = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($admin['username']) . "</td>";
                echo "<td>";
                echo "<a href=\"edit_admin.php?id=" . urlencode($admin['id']) . "\">Edit</a> | ";
                echo "<a href=\"delete_admin.php?id=" . urlencode($admin['id']) . "\" onclick=\"return confirm('Are you sure?');\">Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>

<?php
// Close the database connection
mysqli_free_result($result);
mysqli_close($connection);
?>