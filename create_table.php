<?php
include 'connect.php';
global $link;

// Creaza tabela
$sql = 'CREATE TABLE persons(
  id INT AUTO_INCREMENT PRIMARY KEY UNIQUE,
  first_name VARCHAR(30),
  last_name VARCHAR(30),
  email VARCHAR(70)
)';

if (mysqli_query($link, $sql)) {
  echo 'Table created successfully.';
} else {
  echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
}

// Inchidere conexiune
mysqli_close($link);
?>