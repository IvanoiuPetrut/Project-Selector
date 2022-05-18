<?php
include 'connect.php';
global $link;

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$group = $_POST['group'];
$email = $_POST['email'];
$password = $_POST['password'];

// insert data into database
$sql = "INSERT INTO users (first_name, last_name, group_id, email, password) VALUES ('$first_name', '$last_name', SELECT id FROM groups WHERE name = '$group', '$email', '$password')";

// Inserare date
// $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$password')";
// $sql2 = "INSERT INTO users(group_id) VALUES ((SELECT id FROM groups WHERE name = '$group'))";

if (mysqli_query($link, $sql)) {
  echo 'Records inserted successfully.';
} else {
  echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
}

// Inchidere conexiune
mysqli_close($link);
?>