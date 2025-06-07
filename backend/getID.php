<?php
// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class GetID{
    public static function getID() {
        return isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 7;
    }
}