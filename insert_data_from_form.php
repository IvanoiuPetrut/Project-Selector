<?php
include 'connect.php';
global $link;

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Inserare date
// $sql = 'INSERT INTO persons (first_name, last_name, email) VALUES ("' . $first_name . '", "' . $last_name . '", "' . $email . '")';
$sql = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$password')";
if (mysqli_query($link, $sql)) {
  echo 'Records inserted successfully.';
} else {
  echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
}

// Inchidere conexiune
mysqli_close($link);
?>