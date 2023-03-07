<?php
// Start the session
session_start();

// Check if user is not logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// Connect to MySQL database using prepared statements
$mysqli = new mysqli('localhost', 'email', 'password', 'database_name');
if ($mysqli->connect_errno) {
    die('Failed to connect to MySQL: ' . $mysqli->connect_error);
}

// Prepare SQL statement to retrieve user's profile information
$stmt = $mysqli->prepare('SELECT * FROM users WHERE email = ?');
$stmt->bind_param('s', $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();

// Fetch user's profile information from MySQL database
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $email = $row['email'];
    $profile_picture = $row['profile_picture'];
} else {
    die('Failed to fetch user profile information');
}

// Close MySQL statement and result set
$stmt->close();
$result->close();

// Connect to MongoDB database
$mongo = new MongoDB\Driver\Manager('mongodb://localhost:27017');

// Prepare MongoDB query to retrieve user's profile information
$filter = ['email' => $_SESSION['email']];
$options = [];
$query = new MongoDB\Driver\Query($filter, $options);

// Fetch user's profile information from MongoDB
$cursor = $mongo->executeQuery('database_name.collection_name', $query);
$document = current($cursor->toArray());
$bio = $document->bio;

// Check if form has been submitted for updating profile information
if (isset($_POST['update_profile'])) {
    // Retrieve form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];

    // Update user's profile information in MySQL database using prepared statements
    $stmt = $mysqli->prepare('UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE email = ?');
    $stmt->bind_param('ssss', $firstname, $lastname, $email, $_SESSION['email']);
    $stmt->execute();

    // Update user's bio information in MongoDB using update command
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
        ['email' => $_SESSION['email']],
        ['$set' => ['bio' => $bio]],
        ['multi' => false, 'upsert' => false]
    );
    $result = $mongo->executeBulkWrite('database_name.collection_name', $bulk);

    // Redirect to profile page
    header('Location: profile.php');
    exit();
}
?>