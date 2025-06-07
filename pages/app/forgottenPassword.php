<?php
 require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>


<head>
    <title>Zapomenuté heslo</title>
</head>
<div class="forgetten-container">
    <h2>Resetujte si heslo <i class="ri-refresh-line"></i></h2>
    <form id="send-form">
        <div class="form-group">
            <label for="username">Email:</label>
            <input type="email" id="username" placeholder="Zadejte e-mail" required>
            <button type="submit">Odeslat</button>
        </div>

        <div class="terms-group" style="display: flex;">
            <input type="checkbox" id="terms" required>
            <label for="terms">Souhlasím se <a target = "__blank" href="./PDF/zpracovaniUdaju.pdf">zpracováním osobních údajů</a></label>
        </div>
        
        <div class="form-links">
            <a href="?prihlaseni" class="reset-password-link">Máte již účet?</a>
            <a href="?registrace">Nemáte účet? Registrovat se</a>
        </div>
    </form>
</div>
<?php require "./pages/footer.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="./JS/cloudApp/forgotten.js"></script>