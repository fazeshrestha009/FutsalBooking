<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../css/allstyles.css">
        <link rel="stylesheet" href="../css/profilestyles.css">
    </head>
</html>
<?php
// Include the database connection file
include '../include/connection.php';
session_start();

// Check if the user is logged in (you can modify this based on your authentication mechanism)
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Retrieve user data from the database
    $userDataQuery = "SELECT * FROM users WHERE id = $userId";
    $userDataResult = mysqli_query($conn, $userDataQuery);

    // Retrieve booking list of the user (Pending)
    $bookingListQuery = "SELECT booking_id, booking_date, booking_time, STATUS, venue FROM bookings WHERE user_id = $userId AND status = 'Pending'";
    $bookingListResult = mysqli_query($conn, $bookingListQuery);

    // Retrieve approved bookings
    $approvedBookingsQuery = "SELECT booking_id, booking_date, booking_time, STATUS, venue FROM bookings WHERE user_id = $userId AND status = 'Approved'";
    $approvedBookingsResult = mysqli_query($conn, $approvedBookingsQuery);

    // Retrieve booking history
    $bookingHistoryQuery = "SELECT booking_id, booking_date, booking_time, STATUS, venue FROM bookings WHERE user_id = $userId";
    $bookingHistoryResult = mysqli_query($conn, $bookingHistoryQuery);

    include '../include/navbar.php';
    echo "<main>";
    echo "<div class='container'>
    <h1>User Profile</h1><br/>";

    // Display user data
    echo "<div class='user-info'>";
    echo "<h2>User Information:</h2>";
    if (mysqli_num_rows($userDataResult) > 0) {
        $userData = mysqli_fetch_assoc($userDataResult);
        echo "<b>Username: </b>" . $userData['username'] . "<br>";
        echo "<b>Full Name: </b>" . $userData['full_name'] . "<br>";
        echo "<b>Email: </b>" . $userData['email'] . "<br>";
        echo "<b>Mobile No.: </b>" . $userData['phone'] . "<br>";
    } else {
        echo "<b>User not found</b>";
    }
    echo "</div>";

    // Display booking list of the user (Pending)
    echo "<div class='booking-list'>";
    echo "<h2>Booking Pending List:</h2>";
    if (mysqli_num_rows($bookingListResult) > 0) {
        echo "<table>
            <tr>
                <th>Booking ID</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                <th>Booking Status</th>
                <th>Venue</th> <!-- New Venue Column -->
            </tr>";
        while ($booking = mysqli_fetch_assoc($bookingListResult)) {
            echo "<tr>
                <td>" . $booking['booking_id'] . "</td>
                <td>" . $booking['booking_date'] . "</td>
                <td>" . $booking['booking_time'] . "</td>
                <td>" . $booking['STATUS'] . "</td>
                <td>" . $booking['venue'] . "</td> <!-- Display Venue -->
            </tr>";
        }
        echo "</table><br>";
    } else {
        echo "<b>No bookings found</b>";
    }
    echo "</div>";

    // Display approved bookings
    echo "<div class='approved-bookings'>";
    echo "<h2>Approved Bookings:</h2>";
    if (mysqli_num_rows($approvedBookingsResult) > 0) {
        echo "<table>
            <tr>
                <th>Booking ID</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                <th>Booking Status</th>
                <th>Venue</th> <!-- New Venue Column -->
            </tr>";
        while ($approvedBooking = mysqli_fetch_assoc($approvedBookingsResult)) {
            echo "<tr>
                <td>" . $approvedBooking['booking_id'] . "</td>
                <td>" . $approvedBooking['booking_date'] . "</td>
                <td>" . $approvedBooking['booking_time'] . "</td>
                <td>" . $approvedBooking['STATUS'] . "</td>
                <td>" . $approvedBooking['venue'] . "</td> <!-- Display Venue -->
            </tr>";
        }
        echo "</table><br>";
    } else {
        echo "<b>No approved bookings found</b>";
    }
    echo "</div>";

    // Display booking history
    echo "<div class='booking-history'>";
    echo "<h2>Booking History:</h2>";
    if (mysqli_num_rows($bookingHistoryResult) > 0) {
        echo "<table>
            <tr>
                <th>Booking ID</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                <th>Booking Status</th>
                <th>Venue</th> <!-- New Venue Column -->
            </tr>";
        while ($bookingHistory = mysqli_fetch_assoc($bookingHistoryResult)) {
            echo "<tr>
                <td>" . $bookingHistory['booking_id'] . "</td>
                <td>" . $bookingHistory['booking_date'] . "</td>
                <td>" . $bookingHistory['booking_time'] . "</td>
                <td>" . $bookingHistory['STATUS'] . "</td>
                <td>" . $bookingHistory['venue'] . "</td> <!-- Display Venue -->
            </tr>";
        }
        echo "</table><br>";
    } else {
        echo "<b>No booking history found</b>";
    }
    echo "</div>";

    // Close the database connection
    mysqli_close($conn);
} else {
    // User is not logged in
    echo "<b>User not logged in</b>";
}
echo "</div>";
echo "</main>";
include '../include/footer.html';
?>
