<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if session variables are not set or reset time has expired (over 8 minutes)
if(!isset($_SESSION["user-email"]) || !isset($_SESSION["reset"]) || !isset($_SESSION["reset-time"])) {
    echo "<script>location.href = '?uvod';</script>";  // Redirect if the session is not valid
} else {
    // Check if the reset time is over 8 minutes
    $reset_time = $_SESSION["reset-time"];
    $current_time = time();
    $time_diff = $current_time - $reset_time;  // Difference in seconds

    if ($time_diff > 480) {  // 480 seconds = 8 minutes
        echo "<script>location.href = '?uvod';</script>";
    }
}
?>
<head>
    <title>Reset</title>
</head>
<div class="login-container">
    <h2>Změna hesla<i class="ri-login-circle-line"></i></h2>
    <form id="login-form" action="#" method="POST">
        <div class="form-group">
            <label for="new-password">Nové heslo:</label>
            <input type="password" name="new-password" id="new-password" placeholder="Zadejte nové heslo" required>
        </div>

        <div class="form-group">
            <label for="password">Heslo:</label>
            <input type="password" name="password" id="password" placeholder="Opakujte nové heslo" required>
        </div>

        <div class="terms-group" style="display: flex;">
            <input type="checkbox" name="terms" id="terms" required>
            <label for="terms">Souhlasím se <a target = "__blank" href="./PDF/zpracovaniUdaju.pdf">zpracováním osobních údajů</a></label>
        </div>

        <div class="form-group">
            <button type="submit">Změnit heslo</button>
        </div>

        <div class="form-links">
            <a href="?zapomenuteHeslo">Zapomněl jsem heslo</a>
            <a href="?registrace">Nemáte účet? Registrovat se</a>
        </div>
    </form>
</div>

<script src="./JS/cloudApp/reset.js"></script>