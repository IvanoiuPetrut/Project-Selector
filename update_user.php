<?php
include 'connect.php';
global $link;

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$group = $_POST['group'];
$email = $_POST['email'];
$password = $_POST['password'];
session_start();
$user_id = $_SESSION['user_id'];
session_write_close();


$first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
$last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
$group = filter_var($group, FILTER_SANITIZE_STRING);
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

$group_id = 'SELECT groups.id FROM groups WHERE name = "'.$group.'"';
$group_id = mysqli_query($link, $group_id);
$group_id = mysqli_fetch_assoc($group_id);
$group_id = $group_id['id'];

if (empty($password)) {
    $sql = 'UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?';
} else {
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $password = hash('sha256', $password);
    $sql = 'UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ? WHERE id = ?';
}

if($stmt = mysqli_prepare($link, $sql)) {
    if (empty($password)) {
        mysqli_stmt_bind_param($stmt, 'sssi', $first_name, $last_name, $email, $user_id);
    } else {
        mysqli_stmt_bind_param($stmt, 'ssssi', $first_name, $last_name, $email, $password, $user_id);
    }
    mysqli_stmt_execute($stmt);

    session_start();
    $_SESSION['user_name'] = $first_name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_password'] = $password;
    header('Location: ../proiect/settings.php');
    session_write_close();
} else {
    echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>