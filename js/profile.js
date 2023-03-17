
$(document).ready(function() {
  // Handle edit profile button click
  $('#edit-profile-btn').click(function(event) {
    event.preventDefault();
    // Enable input fields
    $('input').prop('disabled', false);
    // Show cancel and save buttons and hide edit button
    $('#cancel-btn').show();
    $('#save-btn').show();
    $(this).hide();
  });

  // Handle cancel button click
  $('#cancel-btn').click(function(event) {
    event.preventDefault();
    // Reset input fields to original values
    $('input').each(function() {
      $(this).val($(this).prop('defaultValue'));
    });
    // Disable input fields
    $('input').prop('disabled', true);
    // Show edit button and hide cancel button
    $('#edit-profile-btn').show();
    $(this).hide();
    $('#save-btn').hide();
  });

  // Handle save button click
  $('#save-btn').click(function(event) {
    event.preventDefault();
    // Disable input fields
    $('input').prop('disabled', true);
    // Show edit button and hide save and cancel buttons 
    $('#edit-profile-btn').show();
    $('#cancel-btn').hide();
    $(this).hide();
    // Send AJAX request to update profile information
    $.ajax({
      type: 'POST',
      url: 'profile.php', // Replace with your own back-end script
      data: $('#profile-form').serialize(),
      success: function(response) {
        // Show success message
        Swal.fire({
          title: 'Profile Updated',
          text: 'Your profile has been successfully updated.',
          icon: 'success',
          confirmButtonText: 'OK'
        });
      },
      error: function(xhr, status, error) {
        // Show error message
        Swal.fire({
          title: 'Error',
          text: 'An error occurred while updating your profile.',
          icon: 'error',
          confirmButtonText: 'OK'
        });
      }
    });
  });

  
  // Handle profile form submission
  $('#profile-form').submit(function(event) {
    event.preventDefault();
    // Disable input fields
    $('input').prop('disabled', true);
    // Show edit button and hide cancel button
    $('#edit-profile-btn').show();
    $('#cancel-btn').hide();
    // Send AJAX request to update profile information
    $.ajax({
      type: 'POST',
      url: 'profile.php', // Replace with your own back-end script
      data: $('#profile-form').serialize(),
      success: function(response) {
        // Show success message
        Swal.fire({
          title: 'Profile Updated',
          text: 'Your profile has been successfully updated.',
          icon: 'success',
          confirmButtonText: 'OK'
        });
      },
      error: function(xhr, status, error) {
        // Show error message
        Swal.fire({
          title: 'Error',
          text: 'An error occurred while updating your profile.',
          icon: 'error',
          confirmButtonText: 'OK'
        });
      }
    });
  });
});