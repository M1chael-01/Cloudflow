<?php
 require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>

<head>
    <title>Přihlášení</title>
</head>
  <div class="login-container">
        <h2>Přihlášení do aplikace <i class="ri-login-circle-line"></i></h2>
        <form id = "login-form" action="#" method="POST">
            <div class="form-group">
                <label for="username">Uživatelské jméno:</label>
                <input type="text" name="name" id="username" placeholder="Zadejte uživatelské jméno" required>
            </div>

            <div class="form-group">
                <label for="password">Heslo:</label>
                <input type="password" name="password" id="password" placeholder="Zadejte heslo" required>
            </div>
            <div class="terms-group" style="display: flex;">
                <input type="checkbox" name="terms" id="terms" required>
                <label for="terms">Souhlasím se <a target = "__blank" href="./PDF/zpracovaniUdaju.pdf">zpracováním osobních údajů</a></label>
            </div>
            <div class="form-group">
                <button>Přihlásit se</button>
            </div>
            <div class="form-links">
                <a href="?zapomenuteHeslo">Zapomněl jsem heslo</a>
                <a href="?registrace">Nemáte účet? Registrovat se</a>
            </div>
        </form>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="./JS/cloudApp/login.js"></script>
    <?php require "./pages/footer.php"; ?>
