<?php
include 'connect.php';
global $link;

session_start();
if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 3) {
  $group_name = $_POST['group_name'];
  $group_name = filter_var($group_name, FILTER_SANITIZE_STRING);

  // check $group_name if its
  $sql = 'INSERT INTO groups (name) VALUES (?)';
  
  if($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, 's', $group_name);
    mysqli_stmt_execute($stmt);

    $_SESSION['success'][] = array('Group added');
    header('Location: ../proiect/admin.php');
  } else {
    $_SESSION['errors'][] = array('Could not add group, try again later');
    header('Location: ../proiect/admin.php');
  }
} else {
  echo 'You are not allowed to be here';
}