<?php
    include 'connect.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    $email = filter_var( $email, FILTER_SANITIZE_EMAIL );
    $password = filter_var( $password, FILTER_SANITIZE_STRING );
    $password = hash('sha256', $password);


    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    if($result = mysqli_query($link, $sql)) {
        if(mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if($row){
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['first_name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_password'] = $row['password'];
                $_SESSION['user_group'] = $row['id_group'];
                $_SESSION['user_role'] = $row['id_role'];
                header('Location: ../proiect/index.php');
                echo "User autentificat: " . $row['first_name'];
                session_write_close();
            } else {
                // header('Location: login.php');
                echo "Userul nu exista";
            }
        } else {
            header('Location: login.php');
            throw new Exception("Userul nu exista");
        }
    } else {
        header('Location: login.php');
            throw new Exception("Userul nu exista");
    }
    
    // mysqli_stmt_close($stmt);
    mysqli_close($link);
?>