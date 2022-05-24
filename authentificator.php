<?php
    include 'connect.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    $email = filter_var( $email, FILTER_SANITIZE_EMAIL );
    $password = filter_var( $password, FILTER_SANITIZE_STRING );
    $password = hash('sha256', $password);
    
    session_start();

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    if($result = mysqli_query($link, $sql)) {
        if(mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if($row){
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['first_name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_password'] = $row['password'];
                $_SESSION['user_role'] = $row['id_role'];
                $_SESSION['user_group'] = $row['id_group'];
                $_SESSION['success'][] = array('You are now logged in');
                header('Location: ../proiect/index.php');
            } else {
                $_SESSION['errors'][] = array('User not found');
                header('Location: ../proiect/login.php');
            }
        } else {
            $_SESSION['errors'][] = array('Wrong email or password');
            header('Location: ../proiect/login.php');
        }
    } else {
        $_SESSION['errors'][] = array('Could not connect to database, please try again later');
        header('Location: ../proiect/login.php');
    }
    
    session_write_close();
    mysqli_close($link);
?>