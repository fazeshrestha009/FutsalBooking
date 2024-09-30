<?php
session_start();
include '../include/connection.php';

// Retrieve the submitted form data
$userID = $_SESSION['user_id'];
$bookingDate = $_POST['booking_date'];
$bookingTime = $_POST['booking_time'];
$selectedField = $_POST['selected_field'];
$venue = $_POST['venue']; // Retrieve the venue from the form

// Perform server-side validation
$errors = array();

if (empty($bookingDate)) {
    $errors[] = "Booking date is required.";
}

if (empty($bookingTime)) {
    $errors[] = "Booking time is required.";
}

// Check if there are any validation errors
if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    header("Location: booking.php");
    exit();
}

// Insert booking into the database
$query = "INSERT INTO bookings (user_id, field_id, booking_date, booking_time, venue, status) 
          VALUES ('$userID','$selectedField', '$bookingDate', '$bookingTime', '$venue', 'Pending')";

if (mysqli_query($conn, $query)) {
    // Redirect to the confirmation page with booking details
    header("Location: ../include/confirmation.php?booking_date=" . urlencode($bookingDate) . 
           "&booking_time=" . urlencode($bookingTime) . 
           "&selected_field=" . urlencode($selectedField) . 
           "&venue=" . urlencode($venue));
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
