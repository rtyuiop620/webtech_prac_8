<?php
session_start();

function confirm_logged_in()
{
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit;
    }
}
?>