<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the user's profile information from the form submission
  $user_id = $_POST['user_id'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $bio = $_POST['bio'];
  
  // Validate input fields
  $errors = array();
  if (empty($name)) {
    $errors['name'] = 'Name is required';
  }
  if (empty($email)) {
    $errors['email'] = 'Email is required';
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Invalid email format';
  }
  
  if (empty($errors)) {
    // Connect to database
    $servername = "localhost";
    $username = "your-username";
    $password = "your-password";
    $dbname = "your-dbname";
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare and execute SQL statement to update user profile information
    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=?, bio=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $email, $phone, $bio, $user_id);
    if ($stmt->execute()) {
      $success_message = "Profile updated successfully";
      
      // Save updated user information in Redis
      $redis = new Redis();
      $redis->connect('localhost', 6379);
      $userData = array(
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'bio' => $bio
      );
      $redis->hMset("user:$user_id", $userData);
      
    } else {
      $error_message = "Error updating profile";
    }
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
  }
}
?>
