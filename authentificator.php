<?php
    include 'connect.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    $email = filter_var( $email, FILTER_SANITIZE_EMAIL );
    $password = filter_var( $password, FILTER_SANITIZE_STRING );
    $password = hash('sha256', $password);


// create a autehntificator
    // $sql = 'SELECT * FROM users WHERE email = ? AND password = ?';
    // if($stmt = mysqli_prepare($link, $sql)) {
    //     mysqli_stmt_bind_param($stmt, 'ss', $email, $password);
    //     mysqli_stmt_execute($stmt);
    //     mysqli_stmt_store_result($stmt);
    //     $result = mysqli_stmt_num_rows($stmt);
    //     if($result == 1) {
    //         session_start();
    //         $_SESSION['user_id'] = mysqli_stmt_fetch_assoc($stmt)['id'];
    //         $_SESSION['user_name'] = mysqli_stmt_fetch_assoc($stmt)['first_name'];
    //         $_SESSION['user_email'] = mysqli_stmt_fetch_assoc($stmt)['email'];
    //         $_SESSION['user_password'] = mysqli_stmt_fetch_assoc($stmt)['password'];
    //         $_SESSION['user_role'] = mysqli_stmt_fetch_assoc($stmt)['id_role'];
    //         header('Location: ../proiect/index.php');
    //         session_write_close();
    //     } else {
    //         echo '<p class="error">Wrong email or password</p>';
    //     }
    // } else {
    //     echo 'ERROR: Could not able to execute ' . $sql . mysqli_error($link);
    // }

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