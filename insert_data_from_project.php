<?php
include 'connect.php';
global $link;

$project_name = $_POST['project_name'];
$project_description = $_POST['project_description'];

// Inserare date
// $sql = 'INSERT INTO persons (first_name, last_name, email) VALUES ("' . $first_name . '", "' . $last_name . '", "' . $email . '")';
$sql = "INSERT INTO projects (name, description) VALUES ('$project_name', '$project_description')";
if (mysqli_query($link, $sql)) {
  header('Location: projects.php');
  exit();
} else {
  echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
}

// Inchidere conexiune
mysqli_close($link);
?>