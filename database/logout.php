<?php

    session_start();
    session_destroy();
    header('Location: ../');

    setcookie("staffast_login_email", $email, time()-3600 * 365, "/");

?>