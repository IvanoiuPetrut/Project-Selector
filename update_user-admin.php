<?php
include 'connect.php';
global $link;

session_start();
if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 3) {
$id = $_POST['id'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];
$group_id = $_POST['group'];
$role_id = $_POST['role'];

// sanitize the data
$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
$first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
$last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$password = filter_var($password, FILTER_SANITIZE_STRING);
$group_id = filter_var($group_id, FILTER_SANITIZE_NUMBER_INT);
$role_id = filter_var($role_id, FILTER_SANITIZE_NUMBER_INT);

$first_name = strtolower($first_name);
$last_name = strtolower($last_name);
$first_name = ucfirst($first_name);
$last_name = ucfirst($last_name);

// check if the password is empty
if (empty($password)) {
    $sql = 'UPDATE users SET id = ?, first_name = ?, last_name = ?, email = ?, id_group = ?, id_role = ? WHERE id = ?';
} else {
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $password = hash('sha256', $password);
    $sql = 'UPDATE users SET id = ?, first_name = ?, last_name = ?, email = ?, password = ?, id_group = ?, id_role = ? WHERE id = ?';
}

if($stmt = mysqli_prepare($link, $sql)) {
    if (empty($password)) {
        mysqli_stmt_bind_param($stmt, 'isssiii', $id, $first_name, $last_name, $email, $group_id, $role_id, $id);
    } else {
        mysqli_stmt_bind_param($stmt, 'issssiii', $id, $first_name, $last_name, $email, $password, $group_id, $role_id, $id);
    }
    mysqli_stmt_execute($stmt);

    header('Location: ../proiect/admin.php');
} else {
    echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
}

mysqli_stmt_close($stmt);
mysqli_close($link);
} else {
    // header('Location: ../proiect/admin.php');
    echo 'You are not the admin';
}

session_write_close();
?>