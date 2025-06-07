<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Start the session only if it's not already started
}

class CheckStorage{
    public static function checkStorage() {
        if(isset($_SESSION["used-storage"]) && isset($_SESSION["max-storage"])){
            // compore used storage with max storage , if if used bigger return false
            if($_SESSION["used-storage"] < $_SESSION["max-storage"]) {
                return true;
            }
            else return false;
        }
        else{
            return false;
        }
    }
}