<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


class CheckCookie {
    public static function checkCookie() {
        if (isset($_COOKIE["cookie"]) && isset($_COOKIE["cookieData"]) 
            && isset($_COOKIE["country"]) && isset($_COOKIE["ip"])
            && isset($_COOKIE["platform"]) && isset($_COOKIE["screenResolution"])
            && isset($_COOKIE["timezone"]) && isset($_COOKIE["userAgent"])) {
            return true;
        } else {
            return false;
        }
    }
}

