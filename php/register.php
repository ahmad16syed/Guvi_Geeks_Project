
<?php

$DATABASE_HOST = 'localhost';

$DATABASE_USER = 'root'; 

$DATABASE_PASS = '';

$DATABASE_NAME = 'guvitask';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);


if(mysqli_connect_error()) { 
    exit('Error connecting to the database ' . mysqli_connect_error());}

if(!isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'])) {
    exit('Empty Field(s)');
}

if (empty($_POST['first_name'] || empty($_POST['last_name'] || empty($_POST['password']) || empty($_POST['email'])))) 
{ 
    exit('Values Empty');

}

if($stmt = $con->prepare('SELECT id, password FROM register WHERE first_name = ?')) { 
    $stmt->bind_param('s', $_POST['first_name']); 
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows>0) {
        echo 'Username Already Exist. Try Again';
    }
    else{
        if($stmt = $con->prepare('INSERT INTO register(first_name, last_name, email, password) VALUES (?, ?, ?, ?)')){
            $stmt->bind_param('ssss', $_POST['first_name'], $_POST['last_name'], $_POST['password'], $_POST['email']); 
            $stmt->execute(); 
            echo 'Successfully Registered';
        }
        else {
            echo 'Error Occurred';
            }
    }
}

$stmt->close();

?>
