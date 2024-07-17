<?php
require_once ('./config/db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM candidate.users WHERE u_id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>