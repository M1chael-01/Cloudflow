<?php
 require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>


<head>
    <title>Změna hesla</title>
</head>

<?php
    if(!isset($_SESSION["user-code"])) {
        echo "<script>location.href = '?uvod'</script>";
    }
?>
<div class="login-container">
    <h2>Změna hesla <i class="ri-login-circle-line"></i></h2>
    <form id="send-form" action="#" method="POST">
        <div class="form-group">
            <label for="password">Nové heslo:</label>
            <input type="password" name="password" id="password" placeholder="Zadejte nové heslo" required>
        </div>
        <div class="form-group">
            <label for="new-password">Opakujte heslo:</label>
            <input type="password" name="new-password" id="new-password" placeholder="Opakujte heslo" required>
        </div>

        <div class="terms-group" style="display: flex;">
            <input type="checkbox" name="terms" id="terms" required>
            <label for="terms">Souhlasím se <a href="#">zpracováním osobních údajů</a></label>
        </div>

        <div class="form-group">
            <button type="submit">Změnit heslo</button>
        </div>

        <div class="form-links">
            <a href="?prihlaseni">Máte již účet</a>
        </div>
    </form>
</div>
<?php require "./pages/footer.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./JS/cloudApp/changePassword.js"></script>

