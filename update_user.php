<?php
include 'connect.php';
global $link;

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$group = $_POST['group'];
$email = $_POST['email'];
$password = $_POST['password'];
$user_id = $_POST['id'];

$first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
$last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
$group = filter_var($group, FILTER_SANITIZE_STRING);
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$password = filter_var($password, FILTER_SANITIZE_STRING);
$password = hash('sha256', $password);

$sql = 'UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ? WHERE id = ?';

if($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ssssi', $first_name, $last_name, $email, $password, $user_id);
    mysqli_stmt_execute($stmt);

    session_start();
    // $_SESSION['user_id'] = mysqli_insert_id($link);
    // $_SESSION['user_name'] = $first_name;
    // $_SESSION['user_email'] = $email;
    // $_SESSION['user_password'] = $password;
    // // $_SESSION['user_role'] = mysqli_insert_id($link);
    // header('Location: ../proiect/settings.php');
    session_write_close();
} else {
    echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>