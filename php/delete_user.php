<?php
// Include the database connection file
include '../include/connection.php';

// Retrieve the user ID from the URL parameter
$userId = $_GET['id'];

if ($userId) {
    // Prepare the delete query to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    
    // Execute the query
    if ($stmt->execute()) {
        echo 'User account deleted successfully.';
    } else {
        echo 'Error deleting user account: ' . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid user ID.";
}

// Close the database connection
mysqli_close($conn);
?>
