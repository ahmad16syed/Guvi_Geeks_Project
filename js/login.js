
$(document).ready(function() {

  // validate login form on submit
  $('#login-form').click(function() {

    var email = $('#email').val();
    var password = $('#password').val();

    // Check if the email is empty
    if (email == "") {
      alert("Please enter your email.");
      return false;
    }

    // Check if the password is empty
    if (password == "") {
      alert("Please enter your password.");
      return false;
    }

    // If all validations are successful, submit the form
    window.location.href = 'profile.html';
  });

});
