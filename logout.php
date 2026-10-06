<?php
    // Start sesiune
    session_start();
    // Golire variable de sesiune
    session_unset();

    // Redirect
    header("Location: index.php");
    exit;
?>