<?php
include 'connect.php';
global $link;

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$group = $_POST['group'];
$email = $_POST['email'];
$password = $_POST['password'];
$id_role =  1;

// sanitize data
$first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
$last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
$group = filter_var($group, FILTER_SANITIZE_STRING);
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$password = filter_var($password, FILTER_SANITIZE_STRING);
$password = hash('sha256', $password);

$first_name = strtolower($first_name);
$first_name = ucfirst($first_name);
$last_name = strtolower($last_name);
$last_name = ucfirst($last_name);

session_start();
$sql = "SELECT * FROM users WHERE email = '$email'";
$count_email = mysqli_num_rows(mysqli_query($link, $sql));

// selecet group id from groups table
$sql = "SELECT id FROM groups WHERE name = '$group'";
if($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, 's', $group);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_group);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

$sql = 'INSERT INTO users (first_name, last_name, email, password, id_group, id_role) VALUES (?, ?, ?, ?, ?, ?)';
if($count_email == 0) {
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ssssii', $first_name, $last_name, $email, $password, $id_group, $id_role);
        mysqli_stmt_execute($stmt);

        $_SESSION['user_id'] = mysqli_insert_id($link);
        $_SESSION['user_name'] = $first_name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_password'] = $password;
        $_SESSION['user_role'] = $id_role;
        $_SESSION['success'][] = array('Registration successful');
        header('Location: ../proiect/index.php');
        session_write_close();
    } else {
        $_SESSION['errors'][] = array('Could not register, try again later');
        header('Location: ../proiect/register.php');
    }
} else {
    $_SESSION['errors'][] = array('Email already exists');
    header('Location: ../proiect/register.php');
}
mysqli_stmt_close($stmt);
mysqli_close($link);
?>