<!DOCTYPE html>
<html>
<head>
  <title>Booking Confirmation</title>
  <link rel="stylesheet" type="text/css" href="../css/allstyles.css">
  <link rel="stylesheet" type="text/css" href="../css/confirmstyles.css">
  <link rel="stylesheet" type="text/css" href="../css/navstyles.css">
</head>
<body>
  <?php 
  session_start();
  
  // Retrieve booking details from the GET request
  $bookingDate = isset($_GET['booking_date']) ? htmlspecialchars($_GET['booking_date']) : 'N/A';
  $bookingTime = isset($_GET['booking_time']) ? htmlspecialchars($_GET['booking_time']) : 'N/A';
  $selectedField = isset($_GET['selected_field']) ? htmlspecialchars($_GET['selected_field']) : 'N/A';
  $venue = isset($_GET['venue']) ? htmlspecialchars($_GET['venue']) : 'N/A';  // Retrieve the venue from the URL
  ?>
  
  <header>
    <nav>
      <ul>
        <li><a href="../index.php">Home</a></li>
        <li><a href="../services.php">Services</a></li>
        <li><a href="../contact.php">Contact Us</a></li>
        <li><a href="../php/booking.php">Booking</a></li>
        
        <!-- User login/logout and profile links -->
        <?php
          if (isset($_SESSION['user'])) {
            echo '<li class="login"><a href="../php/logout.php">Logout</a></li>';
            echo '<li class="login"><a href="../php/user_profile.php">User Profile</a></li>';
          } else {
            echo '<li class="login"><a href="../php/login.php">Login</a></li>';
            echo '<li class="login"><a href="../php/registration.php">Register</a></li>';
          }
        ?>
      </ul>
    </nav>
  </header>
  
  <main>
    <div class="container">
      <h1>Booking Confirmation</h1>
      <div class="confirmation">
        <p>Your booking has been successfully processed!</p>
        <div class="tbl">
          <table>
            <tr>
              <th colspan="2">Booking Details:</th>
            </tr>
            <tr>
              <td><strong>Venue:</strong></td>
              <td><?php echo $venue; ?></td> <!-- Display venue -->
            </tr>
            <tr>
              <td><strong>Date:</strong></td>
              <td><?php echo $bookingDate; ?></td> <!-- Display booking date -->
            </tr>
            <tr>
              <td><strong>Time:</strong></td>
              <td><?php echo $bookingTime; ?></td> <!-- Display booking time -->
            </tr>
            <tr>
              <td><strong>Field:</strong></td>
              <td><?php echo $selectedField; ?></td> <!-- Display field -->
            </tr>
          </table>
        </div>
        <p>Thank you for using our Futsal Reservation System.</p>
      </div>
    </div>
  </main>

  <?php include 'footer.html'; ?>
  <script src="../js/confirmation.js"></script>
</body>
</html>
