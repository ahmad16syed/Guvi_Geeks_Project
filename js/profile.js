$(document).ready(function() {
    // Retrieve user profile details via AJAX call to PHP backend
    $.ajax({
      url: 'php/profile.php',
      method: 'GET',
      dataType: 'json',
      success: function(data) {
        // If successful, update UI with user profile details
        $('#profile-name').text(data.name);
        $('#profile-email').text(data.email);
        $('#profile-phone').text(data.phone);
        $('#profile-address').text(data.address);
        $('#profile-image').attr('src', 'assets/images/' + data.image);
      },
      error: function(xhr, status, error) {
        // If there's an error, display an error message
        console.log(error);
        alert('Error retrieving user profile details.');
      }
    });
  
    // Handle logout button click event
    $('#logout-btn').on('click', function() {
      // Clear local storage and redirect to login page
      localStorage.clear();
      window.location.href = 'login.html';
    });

});