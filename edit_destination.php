<?php
include("../includes/session.php");
include("../includes/db_connection.php");
include("../includes/functions.php");
confirm_logged_in();

// Check if destination ID is provided
if (isset($_GET['id'])) {
    $destination_id = intval($_GET['id']);

    // Fetch the destination details from the database
    $query = "SELECT * FROM destinations WHERE id = {$destination_id} LIMIT 1";
    $result = mysqli_query($connection, $query);

    if ($destination = mysqli_fetch_assoc($result)) {
        // Destination found, store details in variables
        $name = htmlspecialchars($destination['name']);
        $location = htmlspecialchars($destination['location']);
        $description = htmlspecialchars($destination['description']);
    } else {
        // Redirect if no destination found
        $_SESSION['message'] = "Destination not found.";
        header("Location: manage_content.php");
        exit;
    }
} else {
    // Redirect if no ID provided
    $_SESSION['message'] = "No destination ID provided.";
    header("Location: manage_content.php");
    exit;
}

// Handle form submission for updating the destination
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $location = mysqli_real_escape_string($connection, $_POST['location']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);

    // Update query
    $query = "UPDATE destinations SET name = '{$name}', location = '{$location}', description = '{$description}' WHERE id = {$destination_id}";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $_SESSION['message'] = "Destination updated successfully.";
        header("Location: manage_content.php");
        exit;
    } else {
        echo "Error updating destination: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Destination</title>
    <link rel="stylesheet" href="../public/styles.css">
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f4f4f9;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            color: #2c3e50;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            color: #333;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #3498db;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Edit Destination</h1>
        <form action="edit_destination.php?id=<?php echo $destination_id; ?>" method="post">
            <p>Name: <input type="text" name="name" value="<?php echo $name; ?>" required></p>
            <p>Location: <input type="text" name="location" value="<?php echo $location; ?>" required></p>
            <p>Description: <textarea name="description" required><?php echo $description; ?></textarea></p>
            <p><input type="submit" value="Update Destination"></p>
        </form>
        <p><a href="manage_content.php">Back to Manage Content</a></p>
    </div>
</body>

</html>

<?php
// Close database connection
mysqli_free_result($result);
mysqli_close($connection);
?>