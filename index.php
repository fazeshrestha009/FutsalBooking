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
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    #map {
      height: 400px; /* Adjust height as needed */
      width: 100%; /* Full width of the container */
      margin-top: 20px; /* Space above the map */
    }
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
    <!-- Welcome Section -->
    <section class="hero-section">
    <div class="container01">
      <div class="hero-content">
        <h1>Welcome to FutsalTicket</h1>
        <p class="pheader">Book your futsal field online.</p>
      </div>
    </div>
  </section>

  <!-- New Map Section -->
  <section id="map-section">
    <div class="container">
      <h2 style="color: green; text-align: center;">Find a Futsal Venue Near You</h2>
      <div id="map"></div>
      <button id="recenterButton" style="margin-top: 10px;">Recenter Map</button>
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
  </section>

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

  <br/>
  <?php include 'include/footer.html'; ?>

  <!-- Include Leaflet.js -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    // Initialize the map
    var map = L.map('map').setView([0, 0], 2); // Set initial coordinates and zoom level

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var userLat, userLon;
    var userMarker;

    // Calculate the distance between two coordinates
    function calculateDistance(lat1, lon1, lat2, lon2) {
      var R = 6371; // Radius of the Earth in km
      var dLat = (lat2 - lat1) * (Math.PI / 180);
      var dLon = (lon2 - lon1) * (Math.PI / 180);
      var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
              Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
              Math.sin(dLon / 2) * Math.sin(dLon / 2);
      var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      var distance = R * c; // Distance in km
      return distance;
    }

    // Display all futsal venues on the map
    function showAllFutsalVenues() {
      futsalVenues.forEach(function(venue) {
        var distance = calculateDistance(userLat, userLon, venue.lat, venue.lon);
        L.marker([venue.lat, venue.lon]).addTo(map)
          .bindPopup(venue.name + '<br>Distance: ' + distance.toFixed(2) + ' km')
          .openPopup();
      });
    }

    // Get user's location
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        userLat = position.coords.latitude;
        userLon = position.coords.longitude;

        map.setView([userLat, userLon], 13); // Set map to user's location
        userMarker = L.marker([userLat, userLon], {
          icon: L.divIcon({
            className: 'user-icon',
            html: '<div style="background-color:red; width: 10px; height: 10px; border-radius: 50%;"></div>'
          })
        }).addTo(map)
          .bindPopup('You are here!')
          .openPopup();

        // Show all futsal venues
        showAllFutsalVenues();
      });
    } else {
      alert("Geolocation is not supported by this browser.");
    }

    // Recenter the map
    function recenterMap() {
      if (userLat !== undefined && userLon !== undefined) {
        map.setView([userLat, userLon], 13);
      } else {
        alert("User location is not available.");
      }
    }

    // Button click event listener for recentering the map
    document.getElementById('recenterButton').addEventListener('click', recenterMap);

    // Futsal venues data
    var futsalVenues = [
      { name: "River Side Futsal", lat: 27.720039684481456, lon: 85.30029304688738 },
      { name: "Samakhusi Futsal", lat: 27.734598651054025, lon: 85.32048339314343 },
      { name: "Kumari Futsal", lat: 27.7139447636494, lon: 85.30803159421912 },
      { name: "Manang Futsal", lat: 27.715465815601913, lon: 85.2827675079748 },
      // Add more venues here
    ];
  </script>
</body>
</html>
