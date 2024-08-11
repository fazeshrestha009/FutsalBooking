<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Futsal Reservation System</title>
  <link rel="stylesheet" type="text/css" href="css/allstyles.css">
  <link rel="stylesheet" type="text/css" href="css/navstyles.css">
  <link rel="stylesheet" type="text/css" href="css/indexstyles.css">
  <link rel="stylesheet" type="text/css" href="css/footerstyles.css">
  <style>
    /* Additional CSS styles or inline styles if needed */
  </style>
</head>
<body>
  <header>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="contact.php">Contact Us</a></li>
        <li><a href="php/booking.php">Booking</a></li>
        <?php
          session_start();
          if (isset($_SESSION['user'])) {
            echo '<li class="login"><a href="php/user_profile.php">User Profile</a></li>';
          } else {
            echo '<li class="login"><a href="php/login.php">Login</a></li>';
            echo '<li class="login"><a href="php/registration.php">Register</a></li>';
          } 
        ?>
      </ul>
    </nav>
  </header>

  <section class="hero-section">
    <div class="container01">
      <div class="hero-content">
        <h1>Welcome to FutsalTicket</h1>
        <p class="pheader">Book your futsal field online.</p>
      </div>
    </div>
  </section>

  <section class="services-section">
    <div class="container"> 
      <div class="service">
        <div class="service-col">
          <div class="service-row">
            <div class="img-container">
              <img src="img/cafe1.png" alt="Cafe Image" class="img001">
            </div>
            <div class="text-container">
              <h5>Cafe</h5>
              <p>A small restaurant selling light meals and drinks.</p>
            </div>
          </div>
          <div class="service-row">
            <div class="img-container">
              <img src="img/snooker1.png" alt="Snooker Image" class="img001">
            </div>
            <div class="text-container">
              <h5>Snooker</h5>
              <p>The word snooker was a well-established derogatory term used to describe inexperienced or first-year military personnel.</p>
            </div>
          </div>
          <div class="service-row">
            <div class="img-container">
              <img src="img/parkin1.png" alt="Parking Lot Image" class="img001">
            </div>
            <div class="text-container">
              <h5>Parking Lot</h5>
              <p>An area where cars or other vehicles may be left temporarily; a car park.</p>
            </div>
          </div>
          <!-- Add more service sections as needed -->
        </div>
      </div>
    </div>
    <section>
      <div class="booking-section">
        <div class="booking-context">
          <h2>Make your reservation now</h2>
        </div>
        <div class="booking-btn">
          <a href="php/booking.php" class="book-now-btn">Book Now</a>
        </div>
      </div>
    </section>
  </section>

  
  <!-- Add map section -->
<section class="map-section">
  <div class="map-container">
    <h2 class="near">Nearby Futsal Fields</h2>
    <div id="map" style="width: 100%; height: 400px;"></div>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Initialize the map
        var map = L.map('map').setView([0, 0], 15); // Default center

        // Set the map tiles using OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Get user's current l ocation
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;

            // Set the map center to the user's location
            map.setView([lat, lng], 15);

            // Add a marker for the user's location
            var userMarker = L.marker([lat, lng]).addTo(map)
              .bindPopup("You are here").openPopup();

            // Fetch and display nearby futsal fields (example function call)
            fetchFutsalLocations(lat, lng, map);
          });
        } else {
          alert("Geolocation is not supported by this browser.");
        }
      });

      // Function to fetch and display nearby futsal fields
      function fetchFutsalLocations(lat, lng, map) {
        fetch('get_futsal_locations.php?lat=' + lat + '&lng=' + lng)
          .then(response => response.json())
          .then(data => {
            data.forEach(location => {
              var marker = L.marker([location.lat, location.lng]).addTo(map);
              marker.bindPopup(`<b>${location.name}</b><br>${location.address}`);
            });
          });
      }
    </script>

    <!-- Include Leaflet.js in the HTML -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  </div>
</section>
