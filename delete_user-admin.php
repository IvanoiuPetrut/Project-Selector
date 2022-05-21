<?php
include 'connect.php';
global $link;

// check if the user is the admin
if($_SESSION['user_role'] != 3) {
  $id = $_GET['id'];
  
$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

$sql = 'DELETE FROM users WHERE id = ?';
if($stmt = mysqli_prepare($link, $sql)) {
  mysqli_stmt_bind_param($stmt, 'i', $id);
  mysqli_stmt_execute($stmt);
  
  header('Location: ../proiect/admin.php');
} else {
  echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
} else {
  header('Location: ../proiect/index.php');  
}

?>