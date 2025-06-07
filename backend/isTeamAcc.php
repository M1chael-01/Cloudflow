<?php

class IsTeamAccount {
    public static function isTeamAccount() {
        // Check if the session role is set and if it's 'team'
        return isset($_SESSION["role"]) && $_SESSION["role"] == "team";
    }
}
