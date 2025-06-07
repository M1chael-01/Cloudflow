<?php
 require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>


<?php
// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION["user-code"]))  {
echo "<script>location.href = '?uvod';</script>"; 
    
}
    if(isset($_POST["reset"]) && isset($_POST["code"])) {
        if(isset($_SESSION["user-code"])) {
            if($_SESSION["user-code"] == $_POST["code"]) {
                // max 3times
                echo "you're welcome";
                $_SESSION["change-password"] = true;
                exit;
            }
            else{
                echo "inccorect";
                exit;
            }
        }
    }
?>
<head>
    <title>Kód</title>
</head>

<div class="reset-password-container">
    <h2>Zadejte váš kód <i class="ri-refresh-line"></i></h2>
    <!-- Form to enter the code for password reset -->
    <form class="reset-code-form">
        <div class="form-group">
            <label for="verification-code">Vložte Váš kód z e-mailu:</label>
            <input type="text" id="verification-code" placeholder="Například: 410000" required>
            <button type="submit">Submit</button>
        </div>

        <div class="terms-group" style="display: flex;">
            <input type="checkbox" id="terms-agreement" required>
            <label for="terms">Souhlasím se <a target = "__blank" href="./PDF/zpracovaniUdaju.pdf">zpracováním osobních údajů</a></label>
        </div>

        <div class="form-links">
            <a href="?prihlaseni" class="back-to-login-link">Máte účet?</a>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./JS/cloudApp/enterCode.js"></script>

<?php require "./pages/footer.php"; ?>

