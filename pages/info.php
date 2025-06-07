<?php
 require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>


<?php
// Start output buffering at the very beginning (before any output)
ob_start();

// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_COOKIE["userExist"])) {
    $GLOBALS["name"] = "Tento uživatel již existuje!";  
    $GLOBALS["description"] = "Pokud máte nějaké otázky, kontaktujte podporu."; 
    $GLOBALS["link"] = "?registrace";
} 

if (isset($_COOKIE["login_success"])) {
    exit; 
}

if (isset($_COOKIE["incorrect_login"]) || isset($_GET["incorrect"])) {
    $GLOBALS["name"] = "Bylo zadáno špatné heslo";  
    $GLOBALS["description"] = "Pokud máte nějaké otázky, kontaktujte podporu."; 
    $GLOBALS["link"] = "?prihlaseni";  // Link to the login page 
}


else if (isset($_COOKIE["user_not_found"])) {
    $GLOBALS["name"] = "Uživatelské jméno nebylo nalezeno";  
    $GLOBALS["description"] = "Zkuste se přihlásit s jiným jménem nebo se registrujte."; 
    $GLOBALS["link"] = "?registrace";  
}
else if (isset($_COOKIE["incorrect"])) {
    $GLOBALS["name"] = "Heslo bylo zadánou špatně";  
    $GLOBALS["description"] = "Zkuste to znova"; 
    $GLOBALS["link"] = "?prihlaseni";  
}
else if (isset($_GET["error"])) {
    $error = $_GET["error"];
    if ($error == "user_exists") {
        $GLOBALS["name"] = "Tento uživatel již existuje!";
        $GLOBALS["description"] = "Pokud máte nějaké otázky, kontaktujte podporu.";
        $GLOBALS["link"] = "../?registrace";
    } elseif ($error == "incorrect_login") {
        $GLOBALS["name"] = "Bylo zadáno špatné heslo";
        $GLOBALS["description"] = "Pokud máte nějaké otázky, kontaktujte podporu.";
        $GLOBALS["link"] = "?prihlaseni";
    } elseif ($error == "user_not_found") {
        $GLOBALS["name"] = "Uživatelské jméno nebylo nalezeno";
        $GLOBALS["description"] = "Zkuste se přihlásit s jiným jménem nebo se registrujte.";
        $GLOBALS["link"] = "?registrace";
    }
}

if(isset($_GET["send-true"])) {
    $GLOBALS["name"] = "E-mail byl úspěšně odeslán!";
        $GLOBALS["description"] = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. ";
        $GLOBALS["link"] = "../index";
}


if(isset($_GET["admin404"])) {
    $GLOBALS["name"] = "Administrátor nebyl nalezen";
    $GLOBALS["description"] = "Zkuste se přihlásit s jiným jménem nebo se registrujte.";
    $GLOBALS["link"] = "?admin-prihlaseni";
}


if(isset($_GET["cloud-app-incorrect"])) {
    $GLOBALS["name"] = "Heslo bylo zadánou špatně";  
    $GLOBALS["description"] = "Zkuste to znova";
    $GLOBALS["link"] = "?prihlaseni";  // Link to login page
}

if(isset($_GET["cloud-app-user-404"])) {
    $GLOBALS["name"] = "Uživatel nebyl nalezen";  
    $GLOBALS["description"] = "Zkuste to znova"; 
    $GLOBALS["link"] = "?prihlaseni";  
}


if(isset($_GET["adminHeslo"])) {
    $GLOBALS["name"] = "Bylo zadané špatné heslo";  
    $GLOBALS["description"] = "Zkuste to znova"; 
    $GLOBALS["link"] = "?admin-prihlaseni";  
}
if (isset($_GET["email-send-true"])) {
    $GLOBALS["name"] = "Email byl úspěšně odeslán."; 
    $GLOBALS["description"] = "Podrobnosti odeslané zprávy najdete v e-mailu.";  
    $GLOBALS["link"] = "?uvod";  
}

if (isset($_GET["zadostTrue"])) {
    $GLOBALS["name"] = "Dotaz byl úspěšně odeslán.";  
    $GLOBALS["description"] = "Váš dotaz byl úspěšně odeslán. Podrobnosti naleznete v e-mailu."; 
    $GLOBALS["link"] = "?uvod";  
}
if (isset($_GET["resetFalse"])) {
    $GLOBALS["name"] = "Uživatel nebyl nalezen.";  
    $GLOBALS["description"] = "Zkontrolujte zadaný e-mail. Možná jste udělal/a chybu při zadávání.";  
    $GLOBALS["link"] = "?zapomenuteHeslo";  
}
if(isset($_GET["admin-heslo-false"])) {
    $GLOBALS["name"] = "Administrátor nebyl nalezen.";
    $GLOBALS["description"] = "Zkontrolujte zadaný token. Možná jste udělal/a chybu při zadávání."; 
    $GLOBALS["link"] = "?admin-zapomenuteHeslo";
}

if(isset($_GET["objednavka-sluzba"])) {
    $GLOBALS["name"] = "Objednávka byla odeslána.";
    $GLOBALS["description"] = "Vaše objednávka byla úspěšně odeslána.Podrobnosti máte v emailu.";  
    $GLOBALS["link"] = "?uvod";
}


?>

<head>
    <title>Informace</title>
    <link rel="stylesheet" href="./styles/others/info.css">
</head>

<body>
    <main>
        <div>
            <?php if (isset($GLOBALS["name"])): ?>
                <i id = "icon" class="ri-information-line"></i>
                <h2><?= htmlspecialchars($GLOBALS["name"]); ?></h2>
                <p><?= htmlspecialchars($GLOBALS["description"]); ?></p>
                <a href="<?= htmlspecialchars($GLOBALS["link"]); ?>"><button>Jít zpět</button></a>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

<?php
// Flush output buffer to send the response to the browser
ob_end_flush();
?>

<?php
    require "./pages/footer.php";
?>
