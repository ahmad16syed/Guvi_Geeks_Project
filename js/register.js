
$(document).ready(function() {
  // select the password and confirm password fields
  var passwordField = $("#password");
  var confirmPasswordField = $("#confirm-password");

  // add a blur event listener to the confirm password field
  confirmPasswordField.blur(function() {
    // check if the passwords are equal
    if (passwordField.val() !== confirmPasswordField.val()) {
      // change the outline color of the confirm password field to red
      confirmPasswordField.css("backgroundColor", "red");
    } else {
      // reset the outline color of the confirm password field
      confirmPasswordField.css("backgroundColor", "green");
    }
  });

  // add a submit event listener to the form
  $("#registration-form").submit(function(event) {
    // check if the passwords are equal
    if (passwordField.val() !== confirmPasswordField.val()) {
      // throw an alert if the passwords are not equal
      console.log("Passwords do not match!");
      event.preventDefault(); // prevent form submission
    }
  });

  // add a click event listener to the reset button
  $("#reset-button").click(function() {
    // clear the values of all input fields
    inputFields.val(" ");
  });
});
