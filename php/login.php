<?php
session_start();

require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password'])) {
    // Generate session ID
    $sessionId = uniqid();

    // Save session in Redis
    $redis->setex($sessionId, 3600, $user['id']);

    // Save session ID in browser localStorage
    echo "<script>window.localStorage.setItem('sessionId', '$sessionId');</script>";

    // Redirect to profile page
    header('Location: profile.php');
    exit();
  } else {
    $loginError = 'Invalid email or password';
  }
}

?>