<?php
include 'connect.php';
global $link;

session_start();
if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 2 || $_SESSION['user_role'] == 3) {
  $id = $_POST['id'];
  $project_name = $_POST['name'];
  $project_description = $_POST['description'];

  // sanitize the data
  $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
  $project_name = filter_var($project_name, FILTER_SANITIZE_STRING);
  $project_description = filter_var($project_description, FILTER_SANITIZE_STRING);

  $sql = 'UPDATE projects SET name = ?, description = ? WHERE id = ?';

  if($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ssi', $project_name, $project_description, $id);
    mysqli_stmt_execute($stmt);

    $_SESSION['success'][] = array('Project updated');
    header('Location: ../proiect/projects.php');
  } else {
    $_SESSION['errors'][] = array('Could not update project, try again later');
    header('Location: ../proiect/projects.php');
  }

  mysqli_stmt_close($stmt);
  mysqli_close($link);
} else {
  echo 'You are not allowed to be here';
}

session_write_close();
?>