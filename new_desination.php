<?php
include("../includes/session.php");
include("../includes/db_connection.php");
include("../includes/functions.php");
confirm_logged_in();

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $location = mysqli_real_escape_string($connection, $_POST['location']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $visible = isset($_POST['visible']) ? 1 : 0;

    // Image upload handling
    $image_path = null; // Default to null if no image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../public/images/";
        $image_name = strtolower(str_replace(" ", "_", $name)) . "_" . time() . "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $target_file = $target_dir . $image_name;

        // Check if the file is a valid image type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

        if (in_array($file_type, $allowed_types)) {
            // Move the file to the images directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = "images/" . $image_name; // Store relative path in database
            } else {
                $message = "Error uploading image.";
            }
        } else {
            $message = "Invalid file type. Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // Insert the new destination into the database
    $query = "INSERT INTO destinations (name, location, description, visible, image_path) ";
    $query .= "VALUES ('{$name}', '{$location}', '{$description}', {$visible}, '{$image_path}')";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $_SESSION['message'] = "Destination added successfully.";
        header("Location: manage_content.php");
        exit;
    } else {
        $message = "Error adding destination: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Destination</title>
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

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        textarea {
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
            transition: background-color 0.3s ease;
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
        <h1>Add New Destination</h1>
        <p style="color: red;"><?php echo $message; ?></p>
        <form action="new_destination.php" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="location">Location:</label>
            <input type="text" name="location" id="location" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>

            <label for="image">Image:</label>
            <input type="file" name="image" id="image" accept="image/*">

            <label>
                <input type="checkbox" name="visible" checked> Visible
            </label>

            <input type="submit" value="Add Destination">
        </form>
        <div class="back-link">
            <a href="manage_content.php">Back to Manage Content</a>
        </div>
    </div>
</body>

</html>

<?php
// Close database connection
mysqli_close($connection);
?>