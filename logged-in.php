<?php

    if ( isset($_SESSION['user_id']) && ! empty ( $_SESSION['user_id'])) {
        echo 'Salut ' . $_SESSION['user_name'];
    } else {
        echo 'Nu esti autentificat';
    }
?>