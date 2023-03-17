<?php
session_start();

require_once('vendor/autoload.php');

$client = new MongoDB\Client('mongodb://localhost:27017');

$db = $client->selectDatabase('my_db');

$collection = $db->selectCollection('users');

// Add Redis configuration
$redis = new Redis();
$redis->connect('localhost', 6379);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $user = $collection->findOne(['email' => $email]);

  if ($user && password_verify($password, $user['password'])) {
    // Generate session ID
    $sessionId = uniqid();

    // Save session in MongoDB
    $sessionData = [
      '_id' => $sessionId,
      'user_id' => $user['_id'],
      'created_at' => new MongoDB\BSON\UTCDateTime(time() * 1000)
    ];
    $collection->insertOne($sessionData);

    // Save session ID in Redis
    $redis->set($sessionId, json_encode($sessionData));

    // Save session ID in browser localStorage
    echo "<script>window.localStorage.setItem('sessionId', '$sessionId');</script>";

    // Redirect to profile page
    header('Location: profile.html');
    exit();
  } else {
    $loginError = 'Invalid email or password';
  }
}

?>
