<?php

// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class RedirectUser{
    public static function redirectUser() {
        if(isset($_SESSION["active"]) && isset($_SESSION["user_id"])) {
        echo "<script>location.href = window.location.href = './dashboard/app'</script>";
        }
    }
}


