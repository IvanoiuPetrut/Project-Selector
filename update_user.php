<?php
include 'connect.php';
global $link;

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$group_name = $_POST['group'];
$email = $_POST['email'];
$password = $_POST['password'];
session_start();
$user_id = $_SESSION['user_id'];
session_write_close();


$first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
$last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
$group_name = filter_var($group_name, FILTER_SANITIZE_STRING);
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

$first_name = strtolower($first_name);
$first_name = ucfirst($first_name);
$last_name = strtolower($last_name);
$last_name = ucfirst($last_name);

// select group id from groups table where group name is $group_name
$sql = "SELECT id FROM groups WHERE name = '$group_name'";
if($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, 's', $group_name);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id_group);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

if (empty($password)) {
    $sql = 'UPDATE users SET first_name = ?, last_name = ?, id_group = ?, email = ? WHERE id = ?';
} else {
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $password = hash('sha256', $password);
    $sql = 'UPDATE users SET first_name = ?, last_name = ?, id_group = ?, email = ?, password = ? WHERE id = ?';
}

if($stmt = mysqli_prepare($link, $sql)) {
    if (empty($password)) {
        mysqli_stmt_bind_param($stmt, 'ssssi', $first_name, $last_name, $id_group, $email, $user_id);
    } else {
        mysqli_stmt_bind_param($stmt, 'sssssi', $first_name, $last_name, $id_group, $email, $password, $user_id);
    }
    mysqli_stmt_execute($stmt);

    session_start();
    $_SESSION['user_name'] = $first_name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_password'] = $password;
    $_SESSION['user_group'] = $row['id_group'];
    header('Location: ../proiect/profile.php');
    session_write_close();
} else {
    echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
?>