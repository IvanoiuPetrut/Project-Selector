<?php
    // Start sesiune
    session_start();
    // Golire variable de sesiune
    session_unset();

    // Redirect
    header("Location: ../proiect/index.php");
    exit;
?>