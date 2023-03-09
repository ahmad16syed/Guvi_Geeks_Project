$(document).ready(function() {
    $('.login-form').submit(function(e) {
      e.preventDefault();
      var email = $('.email').val();
      var password = $('.password').val();
      $.ajax({
        type: 'POST',
        url: 'php/login.php',
        data: {email: email, password: password},
        success: function(data) {
          if (data == "success") {
            window.location.href = "profile.php";
          } else {
            alert(data);
          }
        }
      });
    });
  });
