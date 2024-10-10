<?php
include 'db/db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: signup.php"); // Redirect to signup.php after successful deletion
        exit();
    } else {
        echo "Error: " . $stmt->error; // Show error message if the query fails
    }
}
?>
