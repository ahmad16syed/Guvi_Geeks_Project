<?php

// Database connection settings
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'guvitask';

// Redis connection settings
$REDIS_HOST = 'localhost';
$REDIS_PORT = 6379;

// Create a PDO instance
try {
    $pdo = new PDO("mysql:host=$DATABASE_HOST;dbname=$DATABASE_NAME", $DATABASE_USER, $DATABASE_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    exit('Error connecting to database: ' . $e->getMessage());
}

// Create a Redis instance
$redis = new Redis();
$redis->connect($REDIS_HOST, $REDIS_PORT);

// Handle form submission
if(isset($_POST['register'])) {

    // Validate form input
    if(empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['password'])) {
        exit('Please fill in all fields');
    }

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check Redis cache for existing email
    $key = "register:email:$email";
    $user_id = $redis->get($key);

    if($user_id) {
        exit('Email address already exists');
    }

    // Check MySQL database for existing email
    $stmt = $pdo->prepare('SELECT id FROM register WHERE email = :email');
    $stmt->execute(['email' => $email]);

    if($stmt->rowCount() > 0) {
        // Cache email to Redis for future requests
        $redis->set($key, $stmt->fetchColumn());
        exit('Email address already exists');
    }

    // Insert new user into database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO register (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)');
    $stmt->execute(['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'password' => $hashed_password]);

    echo 'Successfully registered. You can now login.';

    // After successful registration, redirect to login page
    header('Location: login.html');
    exit;

}

// Close database connection
$pdo = null;

// Close Redis connection
$redis->close();

?>
