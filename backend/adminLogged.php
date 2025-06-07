<?php

// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class AdminLogged{
    public static function adminLogged() {
        if(isset($_SESSION["admin-logged"]) && isset($_SESSION["admin-id"]))  return true;
        else return false;

    }
}