<?php
include 'connect.php';
global $link;

// get id from url
$project_id = $_GET['id']; //id-ul proiectuiului
session_start();
$user_id = $_SESSION['user_id']; //id-ul utilizatorului
$user_group_id = $_SESSION['user_group']; //id-ul grupului utilizatorului

if (empty($project_id)) {
    header('Location: ../proiect/index.php');
    exit();
}
// error array


// check if the how many projects a group has
$sql = 'SELECT COUNT(*) FROM chosen_projects WHERE id_group = ? AND id_project = ?';
if($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ii', $user_group_id, $project_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count_group_projects);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['errors'][] = array('Could not add project, try again later');
}


if ($count_group_projects < 2) {
    $sql = 'INSERT INTO chosen_projects (id_user, id_group, id_project) VALUES (?, ?, ?)';
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, 'iii', $user_id, $user_group_id, $project_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['success'][] = array('Project added');
        header('Location: ../proiect/projects.php');
    } else {
        $_SESSION['errors'][] = array('Could not add project, try again later');
    }
    } else {
        $_SESSION['errors'][] = array('Only 2 projects per group allowed');
    }

    header('Location: ../proiect/projects.php');

session_write_close();
mysqli_close($link);
?>