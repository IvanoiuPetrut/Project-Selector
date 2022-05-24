<?php
include 'connect.php';
global $link;

session_start();
// check if the user is the professor
if($_SESSION['user_role'] == 2 || $_SESSION['user_role'] == 3) {
  
  $id = $_GET['id'];
  $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

  $sql = 'DELETE FROM projects WHERE id = ?';
  if($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    
    $_SESSION['success'][] = array('Project deleted');
    header('Location: ../proiect/projects.php');
  } else {
    $_SESSION['errors'][] = array('Could not delete project, try again later');
  }

  mysqli_stmt_close($stmt);
} else {
  header('Location: ../proiect/index.php');
}

session_write_close();
mysqli_close($link);
?>