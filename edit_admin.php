<?php
include("../includes/session.php");
include("../includes/db_connection.php");
include("../includes/functions.php");
confirm_logged_in();

// Check if admin ID is provided
if (isset($_GET['id'])) {
    $admin_id = intval($_GET['id']);

    // Fetch the admin details from the database
    $query = "SELECT * FROM admins WHERE id = {$admin_id} LIMIT 1";
    $result = mysqli_query($connection, $query);

    if ($admin = mysqli_fetch_assoc($result)) {
        // Store details in variables
        $username = htmlspecialchars($admin['username']);
    } else {
        // Redirect if admin not found
        $_SESSION['message'] = "Admin not found.";
        header("Location: manage_admins.php");
        exit;
    }
} else {
    // Redirect if no ID provided
    $_SESSION['message'] = "No admin ID provided.";
    header("Location: manage_admins.php");
    exit;
}

// Handle form submission for updating the admin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $hashed_password = hash("sha256", $password); // Hash the new password

    // Update query
    $query = "UPDATE admins SET username = '{$username}', hashed_password = '{$hashed_password}' WHERE id = {$admin_id}";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $_SESSION['message'] = "Admin updated successfully.";
        header("Location: manage_admins.php");
        exit;
    } else {
        echo "Error updating admin: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Admin</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Admin</h1>
        <form action="edit_admin.php?id=<?php echo $admin_id; ?>" method="post">
            <p>Username: <input type="text" name="username" value="<?php echo $username; ?>" required></p>
            <p>Password: <input type="password" name="password" required></p>
            <p><input type="submit" value="Update Admin"></p>
        </form>
        <p><a href="manage_admins.php">Back to Manage Admins</a></p>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_free_result($result);
mysqli_close($connection);
?>