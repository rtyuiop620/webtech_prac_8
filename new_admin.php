<?php include("../includes/session.php");
confirm_logged_in(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>New Admin</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>

<body>
    <div class="container">
        <h1>Add New Admin</h1>
        <form action="create_admin.php" method="post">
            <p>Username: <input type="text" name="username" required></p>
            <p>Password: <input type="password" name="password" required></p>
            <p><input type="submit" value="Add Admin"></p>
        </form>
    </div>
</body>

</html>