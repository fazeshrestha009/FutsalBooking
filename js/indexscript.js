// Check if the user is logged in
const isLoggedIn = true; // Replace with your logic to check if the user is logged in

// Add event listener to the window load event
window.addEventListener('load', function() {
  // Check if the user is logged in
  if (isLoggedIn) {
    // User is logged in, show the user profile icon
    const userProfileIcon = document.createElement('li'); // Create a list item
    const userProfileLink = document.createElement('a');  // Create an anchor element
    
    userProfileLink.href = 'php/profile.php'; // Replace with the actual URL of the user profile page
    userProfileLink.innerHTML = '<img src="img/user-icon.png" alt="User Profile" style="width:24px;height:24px;">'; // Replace with the actual path to the user profile icon image
    
    userProfileIcon.appendChild(userProfileLink); // Append the link to the list item
    document.querySelector('nav ul').appendChild(userProfileIcon); // Append the list item to the navigation bar
  }
});

