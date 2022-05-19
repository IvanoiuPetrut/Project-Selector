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
// create variable with id group from select option

$sql = 'INSERT INTO users (first_name, last_name, email, password, id_group, id_role) VALUES (?, ?, ?, ?, ?, ?)';

if($stmt = mysqli_prepare($link, $sql)) {
    $id_group = 'SELECT id FROM groups WHERE name = "'.$group.'"';
    mysqli_stmt_bind_param($stmt, 'ssssii', $first_name, $last_name, $email, $password, $id_group, $id_role);
    mysqli_stmt_execute($stmt);
    echo 'Records inserted successfully.';
} else {
    echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
}

// select id froum groups where group name is the same as the one from the form

// insert data into database
// $sql = "INSERT INTO users (first_name, last_name, email, password, id_group, id_role) VALUES ('$first_name', '$last_name', '$email', '$password', (SELECT id FROM groups WHERE name = '$group'), 1)";
// $sql = "INSERT INTO users (first_name, last_name, group_id, email, password) VALUES ('$first_name', '$last_name', '$id_group', '$email', '$password')";


// Inserare date
// $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$password')";
// $sql2 = "INSERT INTO users(group_id) VALUES ((SELECT id FROM groups WHERE name = '$group'))";

mysqli_stmt_close($stmt);

mysqli_close($link);
?>