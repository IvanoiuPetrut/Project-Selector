<?php

include 'connect.php';
global $link;

// Creaza baza de date
$sql = 'CREATE DATABASE demo';

if (mysqli_query($link, $sql)) {
  echo 'Database created successfully';
} else {
  echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
}

// Inchidere conexiune
mysqli_close($link);
?>
