<?php
include 'connect.php';
global $link;

session_start();
if((isset($_SESSION['user_id']) && $_SESSION['user_role'] == 1)) {
  $id = $_GET['id'];

  // sanitize the data
  $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
  // update chosen_project table in status = 1
  $sql = 'UPDATE chosen_projects SET status = 1 WHERE id = ?';
  if($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $_SESSION['success'][] = array('Project completed');
    header('Location: ../proiect/profile.php');
  } else {
    $_SESSION['errors'][] = array('Could not approve project, try again later');
    header('Location: ../proiect/profile.php');
  }
}


session_write_close();
mysqli_close($link);
?>