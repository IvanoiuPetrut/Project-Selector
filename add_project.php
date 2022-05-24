<?php
include 'connect.php';
global $link;

session_start();
$project_id = $_GET['id']; 
$user_id = $_SESSION['user_id']; 
$user_group_id = $_SESSION['user_group'];
$status = 0; 

if (empty($project_id)) {
    header('Location: ../proiect/index.php');
    exit();
}

// check if the how many projects a group has
// check if the user is a student 
if($_SESSION['user_role'] == 1)
{
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
    

    // check if the student already took this project
    $sql = 'SELECT COUNT(*) FROM chosen_projects WHERE id_user = ? AND id_project = ?';
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $project_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count_user_projects);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['errors'][] = array('Could not add project, try again later');
    }
    

    if($count_user_projects == 0) {
        if ($count_group_projects < 2) {
            $sql = 'INSERT INTO chosen_projects (id_user, id_group, id_project, status) VALUES (?, ?, ?, ?)';
            if($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, 'iiii', $user_id, $user_group_id, $project_id, $status);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                
                $_SESSION['success'][] = array('Project added');
                header('Location: ../proiect/projects.php');
            } else {
                echo mysqli_error($link);
            }
        } else {
            $_SESSION['errors'][] = array('Only 2 projects per group allowed');
        }
    } else {
        $_SESSION['errors'][] = array('You already took this project');
    }
        header('Location: ../proiect/projects.php');
}

session_write_close();
mysqli_close($link);
?>