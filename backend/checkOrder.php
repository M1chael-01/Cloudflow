<?php

class CheckOrder {
    
    public static function checkOrder() {
        if (session_status() == PHP_SESSION_NONE) {
            // Session has not been started yet, so start the session
            session_start();
        }
        if (isset($_SESSION["products"])) {
            // Corrected count() check
            if (count($_SESSION["products"]) > 0) {
                return true;
            } else {
                return false;
            }
        }
        return false; // If products are not set in the session
    }
}
