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
  /* Basic Reset */
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif; /* A more modern and sleek font */
  }

  html, body {
    height: 100%;
    background-color: #f4f4f9; /* Softer background for better contrast */
    color: #333;
    line-height: 1.6;
  }

  /* Sidebar Styles */
  #sidebar {
    width: 400px;
    height: calc(100vh - 120px);
    background-color: #ffffff; /* Clean white background */
    border-right: 2px solid #e0e0e0;
    padding: 30px;
    position: fixed;
    top: 92px;
    left: 0;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
  }

  /* Map Styles */
  #map {
    height: calc(100vh - 120px);
    width: calc(80% - 100px);
    margin-left: 150px; /* Adjusted for sidebar width */
    position: center;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Subtle shadow */
  }

  /* Button Container */
  .button-container {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
  }

  /* Button Styles */
  #recenterButton {
    padding: 12px 25px;
    background-color: #27ae60;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-left: 450px;
  
    transition: background-color 0.3s ease, transform 0.2s ease;
  }

  #recenterButton:hover {
    background-color: #2ecc71;
    transform: translateY(-3px); /* Slight hover lift */

  }

  /* Container for Main Content */
  .container {
    margin-left: 320px; /* Adjusted for sidebar width */
    padding: 20px;
  }

  /* Footer Styles */
  footer {
    clear: both;
    text-align: center;
    padding: 20px;
    background-color: #1b1b1f; /* Darker background */
    color: white;
  }

  /* Sidebar Search Input */
  #sidebar input[type="text"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1em;
  }

  /* Sidebar Venue Button */
  #sidebar .venue-button {
    display: block;
    width: 100%;
    padding: 15px;
    margin-bottom: 10px;
    background-color: #27ae60;
    color: white;
    border: none;
    border-radius: 5px;
    text-align: left;
    font-size: 1em;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }

  #sidebar .venue-button:hover {
    background-color: #2ecc71;
    transform: translateY(-3px); /* Slight hover lift */
  }

  /* Book Now Button Styles */
  .book-now-button {
    display: block;
    padding: 12px;
    background-color: #e74c3c;
    color: white;
    border: none;
    border-radius: 5px;
    text-align: center;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }

  .book-now-button:hover {
    background-color: #c0392b;
    transform: translateY(-3px); /* Slight hover lift */
  }
</style>

</head>
<body>
  <header>
    <nav>
      <ul>
        <h2 style="color: #00ff00;  text-align:left; margin-right:50px;">Welcome to the Futsal Booking</h2>
        <h2 style=""><li><a href="index.php">Home</a></li></h2>
        <h2 style=""><li><a href="contact.php">Contact Us</a></li></h2>
        <h2 style=""><li><a href="php/booking.php">Booking</a></li></h2>
        <?php
session_start();

// Check if the user is logged in
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
  
  
  <!-- Sidebar for venues and search -->
  <div id="sidebar">
    <input type="text" id="searchBar" placeholder="Search venues...">
    <div id="venueList">
      <!-- Venue buttons will be added here dynamically -->
    </div>
  </div>

  <!-- New Map Section -->
  <section id="map-section">
    <div class="container">
      <h2 style="color:green;  text-align: center;">Find a Futsal Venue Near You</h2>
      <div id="map"></div>
      <button id="recenterButton" style="margin-top: 10px;">Recenter Map</button>
    </div>
  </section>

  

  <footer>
    <?php include 'include/footer.html'; ?>
  </footer>

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

    // Display all futsal venues on the map of system
    function showAllFutsalVenues() {
      futsalVenues.forEach(function(venue) {
        var distance = calculateDistance(userLat, userLon, venue.lat, venue.lon);
        L.marker([venue.lat, venue.lon]).addTo(map)
          .bindPopup(venue.name + '<br>Distance: ' + distance.toFixed(2) + ' km')
          .openPopup();
      });
    }

    <!-- Venue button generation inside the addVenueButtons and filterVenues functions -->
// Add venue buttons to the sidebar
function addVenueButtons() {

  var venueList = document.getElementById('venueList');
  venueList.innerHTML = ''; // Clear previous buttons

  // Calculate distance for each venue and sort by distance
  var venuesWithDistance = futsalVenues.map(function(venue) {
    var distance = calculateDistance(userLat, userLon, venue.lat, venue.lon);
    return {
      ...venue,
      distance: distance
    };
  });

  // Sort venues by distance (ascending order)
  venuesWithDistance.sort(function(a, b) {
    return a.distance - b.distance;
    var bookingPage = isLoggedIn ? `php/booking.php?venue=${encodeURIComponent(venue.name)}` : 'php/registration.php';

  });

  // Create buttons for sorted venues
  venuesWithDistance.forEach(function(venue) {
    var buttonContainer = document.createElement('div');
    buttonContainer.style.marginBottom = '10px'; // Space between buttons

    var venueButton = document.createElement('button');
    venueButton.className = 'venue-button';
    venueButton.innerHTML = `${venue.name}<br>Distance: ${venue.distance.toFixed(2)} km`;
    venueButton.addEventListener('click', function() {
      map.setView([venue.lat, venue.lon], 15);
      L.popup()
        .setLatLng([venue.lat, venue.lon])
        .setContent(`<b>${venue.name}</b><br>Distance: ${venue.distance.toFixed(2)} km`)
        .openOn(map);
    });

    // Check if the user is logged in using PHP
    var isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
    var bookingPage = isLoggedIn ? `php/booking.php?venue=${encodeURIComponent(venue.name)}` : 'php/registration.php';

    var bookButton = document.createElement('a');
    bookButton.href = bookingPage; // Dynamic redirection based on login status and venue name
    bookButton.className = 'book-now-button';
    bookButton.innerHTML = 'Book Now';

    buttonContainer.appendChild(venueButton);
    buttonContainer.appendChild(bookButton);

    venueList.appendChild(buttonContainer);
  });
}


function filterVenues() {
  var searchQuery = document.getElementById('searchBar').value.toLowerCase();
  var filteredVenues = futsalVenues.filter(function(venue) {
    return venue.name.toLowerCase().includes(searchQuery);
  });

  // Calculate distance for each venue and sort by distance
  var venuesWithDistance = filteredVenues.map(function(venue) {
    var distance = calculateDistance(userLat, userLon, venue.lat, venue.lon);
    return {
      ...venue,
      distance: distance
    };
  });

  // Sort venues by distance (ascending order)
  venuesWithDistance.sort(function(a, b) {
    return a.distance - b.distance;
  });

  // Update the venue list based on the filtered and sorted results
  var venueList = document.getElementById('venueList');
  venueList.innerHTML = ''; // Clear previous buttons
  venuesWithDistance.forEach(function(venue) {
    var buttonContainer = document.createElement('div');
    buttonContainer.style.marginBottom = '10px'; // Space between buttons

    var venueButton = document.createElement('button');
    venueButton.className = 'venue-button';
    venueButton.innerHTML = `${venue.name}<br>Distance: ${venue.distance.toFixed(2)} km`;
    venueButton.addEventListener('click', function() {
     map.setView([venue.lat, venue.lon], 15);
    L.popup()
    .setLatLng([venue.lat, venue.lon])
    .setContent(`<b>${venue.name}</b><br>Distance: ${venue.distance.toFixed(2)} km`)
    .openOn(map);
});

    // Check if the user is logged in using PHP
    var isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
    var bookingPage = isLoggedIn ? `php/booking.php?venue=${encodeURIComponent(venue.name)}` : 'php/registration.php';

    var bookButton = document.createElement('a');
    bookButton.href = bookingPage; // Dynamic redirection based on login status
    bookButton.className = 'book-now-button';
    bookButton.innerHTML = 'Book Now';

    buttonContainer.appendChild(venueButton);
    buttonContainer.appendChild(bookButton);

    venueList.appendChild(buttonContainer);
  });
}

    // Get user's location
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        userLat = position.coords.latitude;
        userLon = position.coords.longitude;

        // Recenter map to user's location and add a marker
        map.setView([userLat, userLon], 12);
        userMarker = L.marker([userLat, userLon], { icon: L.icon({ iconUrl: 'https://maps.gstatic.com/mapfiles/ms2/micons/man.png' }) })
          .addTo(map)
          .bindPopup('You are here')
          .openPopup();

        // Show all venues on the map
        showAllFutsalVenues();
        // Add venue buttons to the sidebar
        addVenueButtons();
      }, function(error) {
        console.error('Geolocation error: ', error);
        alert('Unable to retrieve your location.');
      });
    } else {
      alert('Geolocation is not supported by this browser.');
    }

    // Recenter the map on user's location when the button is clicked
    document.getElementById('recenterButton').addEventListener('click', function() {
      if (userLat && userLon) {
        map.setView([userLat, userLon], 12);
        userMarker.openPopup();
      } else {
        alert('User location is not available.');
      }
    });

    // Add event listener for search input
    document.getElementById('searchBar').addEventListener('input', filterVenues);

    // Example data for futsal venues
    var futsalVenues = [
      { name: "River Side Futsal", lat: 27.720039684481456, lon: 85.30029304688738 },
      { name: "Samakhusi Futsal", lat: 27.734598651054025, lon: 85.32048339314343 },
      { name: "Kumari Futsal", lat: 27.7139447636494, lon: 85.30803159421912 },
      { name: "Manang Futsal", lat: 27.715465815601913, lon: 85.2827675079748 },
      { name: "Nepa Futsal", lat: 27.715465815601913, lon: 85.29664974173643 },
    ];
  </script>
</body>
</html>
