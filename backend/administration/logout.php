<?php

if (isset($_GET["true"])) {

    // Start the session if it hasn't been started yet
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Unset session variables
    unset($_SESSION["admin-logged"]);
    unset($_SESSION["admin-id"]);

    // Get the referring URL or use 'default_page.php' if not available
    $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'default_page.php';

    // Redirect the user using JavaScript
    echo "<script>window.location.href = '$redirect_url';</script>";

}
?>
