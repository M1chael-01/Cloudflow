<?php

class Visits {
    
    public static function createSessionIfNot() {
        
        // Start the session only if it hasn't been started yet
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["has_visited"])) {
            // Create a session variable if it doesn't exist
            $_SESSION["has_visited"] = true;
           
        }

    }
}

