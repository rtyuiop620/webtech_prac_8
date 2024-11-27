<?php
function find_all_destinations()
{
    global $connection;
    $query = "SELECT * FROM destinations ORDER BY name ASC";
    return mysqli_query($connection, $query);
}
?>