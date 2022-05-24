<?php

    if ( isset($_SESSION['user_id']) && ! empty ( $_SESSION['user_id'])) {
        // echo user name
        echo '<p class="welcome-message">Welcome <span class="name">' . $_SESSION['user_name'] . '</span></p>';
    } else {
        echo '<p class="welcome-message">You are not logged in</p>';
    }
?>