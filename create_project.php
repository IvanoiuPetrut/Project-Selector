<?php
include 'connect.php';
global $link;

session_start();
$project_name = $_POST['project_name'];
$project_description = $_POST['project_description'];

//check if the project name is already in use
$sql = 'SELECT * FROM projects WHERE name = ?';

if($stmt = mysqli_prepare($link, $sql)) {
  mysqli_stmt_bind_param($stmt, 's', $project_name);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  $result = mysqli_stmt_num_rows($stmt);
  mysqli_stmt_close($stmt);
} else {
  echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
}

// check if the project name is already in use
if ($result > 0) {
  $_SESSION['errors'][] = array('Project name already in use');
  header('Location: ../proiect/projects.php');
} else {
  $sql = 'INSERT INTO projects (name, description) VALUES (?, ?)';

  if($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ss', $project_name, $project_description);
    mysqli_stmt_execute($stmt);

    $_SESSION['success'][] = array('Project added');
    header('Location: ../proiect/projects.php');
  } else {
    $_SESSION['errors'][] = array('Could not add project, try again later');
    header('Location: ../proiect/projects.php');
  }

  mysqli_stmt_close($stmt);
}

session_write_close();
mysqli_close($link);
?>