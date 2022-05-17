<?php

include 'connect.php';
global $link;

// Query de tip `prepared statement`
$sql = 'INSERT INTO persons (first_name, last_name, email) VALUES (?, ?, ?)';

if ($stmt = mysqli_prepare($link, $sql)) {
  // Legare variabile de `prepared statement` ca si parametri
  // b — binar (imagine, fisier PDF, etc.)
  // d — double (variabila float)
  // i — integer (variabila integer)
  // s — string (text)

  
  /* Setare parametri */
  $first_name = 'Hermione';
  $last_name = 'Granger';
  $email = 'hermionegranger@mail.com';
  // Bind parameters
  mysqli_stmt_bind_param($stmt, 'sss', $first_name, $last_name, $email);

  mysqli_stmt_execute($stmt);

  echo 'Records inserted successfully.';
} else {
  echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
}

// Inchidere statement
mysqli_stmt_close($stmt);

// Inchidere conexiune
mysqli_close($link);
?>