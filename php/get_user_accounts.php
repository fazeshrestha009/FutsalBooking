<?php
// Include the database connection file
include '../include/connection.php';

// Fetch user accounts from the database
$query = "SELECT id, username, full_name, email, phone FROM users";
$result = mysqli_query($conn, $query);

// Loop through each user record and display them in the table
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['full_name'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['phone'] . "</td>";
        echo "<td><a class='user-action-link' href='delete_user.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No user accounts found.</td></tr>";
}

// Close the database connection
mysqli_close($conn);
?>
