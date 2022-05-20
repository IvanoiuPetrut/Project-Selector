<?php
include 'connect.php';
global $link;

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$group = $_POST['group'];
$email = $_POST['email'];
$password = $_POST['password'];
$password = hash('sha256', $password);
$id_role =  1;

$sql = 'INSERT INTO users (first_name, last_name, email, password, id_group, id_role) VALUES (?, ?, ?, ?, ?, ?)';

if($stmt = mysqli_prepare($link, $sql)) {
    $id_group = 'SELECT id FROM groups WHERE name = "'.$group.'"';
    mysqli_stmt_bind_param($stmt, 'ssssii', $first_name, $last_name, $email, $password, $id_group, $id_role);
    mysqli_stmt_execute($stmt);

    session_start();
    $_SESSION['user_id'] = mysqli_insert_id($link);
    $_SESSION['user_name'] = $first_name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_password'] = $password;
    $_SESSION['user_role'] = $id_role;
    header('Location: ../proiect/index.php');
    session_write_close();
} else {
    echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>