<?php
 require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>

<head>
    <title>Registrace</title>
</head>

<div class="register-container">
    <h2>Registrace do aplikace <i class="ri-stack-line"></i></h2>
    <form id="register-form">
        <div class="form-group">
            <div class="input-container">
                <label for="name">Jméno:</label>
                <input type="text" id="name" placeholder="Zadejte jméno">
            </div>
            <div class="input-container">
                <label for="email">E-mail:</label>
                <input type="email" id="email" placeholder="Zadejte e-mail">
            </div>
        </div>

        <div class="form-group">
            <div class="input-container">
                <label for="password">Heslo:</label>
                <input type="password" id="password" placeholder="Zadejte heslo">
            </div>
            <div class="input-container">
                <label for="account-type">Typ účtu:</label>
                <select id="account-type" onchange="toggleTeamName()">
                    <option value="individual">Osobní</option>
                    <option value="team">Tým</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="input-container" id="team-name-container" style="display:none;">
                <label for="team-name">Název týmu:</label>
                <input type="text" id="team-name" placeholder="Zadejte název týmu">
            </div>
        </div>

        <div class="terms-group">
            <input type="checkbox" id="terms">
            <label for="terms">Souhlasím se <a target = "__blank" href="./PDF/zpracovaniUdaju.pdf">zpracováním osobních údajů</a></label>
        </div>

        <button type="submit" class="register-btn">Registrovat se</button>
    </form>

    <div class="form-links">
        <a href="?prihlaseni">Máte účet? Přihlaste se</a>
    </div>
</div>

<?php
   
    if(isset($_SESSION["active"]) && isset($_SESSION["user_id"])) {
        // user is loggin
    }

    if(isset($_COOKIE["userExist"])){
        // echo "<script>window.location.href = '?info=uzivatel=false';</script>";
        }

?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./JS/cloudApp/createProfile.js"></script>

<!-- load a footer -->
<?php require "./pages/footer.php"; ?>

