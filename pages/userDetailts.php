<?php
require "./pages/routing.php";

// start session if not already started
if(session_status() == PHP_SESSION_NONE) { session_start();}?>

<?php
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>


<head>
    <title>Fakturační údaje</title>
</head>
<body>

<div id="user-details-container">
    <!-- Step Indicator -->
    <div class="step-indicator">
        <div class="step">1 NÁKUPNÍ KOŠÍK</div>
        <div class="step">2 DOPRAVA A PLATBA</div>
        <div class="step active">3 VAŠE ÚDAJE</div>
    </div>

    <!-- Form Header -->
    <form id="user-details-form">
        <div class="form-row">
            <!-- First Name -->
            <input type="text" class="form-input" name="user_first_name" placeholder="Vaše křestní jméno" required 
                value="<?= isset($_SESSION['user_details']['first_name']) ? htmlspecialchars($_SESSION['user_details']['first_name']) : ''; ?>" />
            
            <!-- Last Name -->
            <input type="text" class="form-input" name="user_last_name" placeholder="Vaše příjmení" required 
                value="<?= isset($_SESSION['user_details']['last_name']) ? htmlspecialchars($_SESSION['user_details']['last_name']) : ''; ?>" />
        </div>

        <div class="form-row">
            <!-- Email -->
            <input type="email" class="form-input" name="user_email" placeholder="Vaše emailová adresa" required 
                value="<?= isset($_SESSION['user_details']['email']) ? htmlspecialchars($_SESSION['user_details']['email']) : ''; ?>" />

            <!-- Phone Number -->
            <input type="tel" class="form-input" name="user_phone" placeholder="Vaše telefonní číslo" required 
                value="<?= isset($_SESSION['user_details']['phone']) ? htmlspecialchars($_SESSION['user_details']['phone']) : ''; ?>" />
        </div>

        <!-- Textarea for Notes -->
        <div class="form-row">
            <textarea class="form-textarea" name="user_notes" placeholder="Poznámky (např. specifické požadavky nebo poznámky k objednávce)"></textarea>
        </div>

        <!-- Terms and Conditions -->
        <div class="terms-group">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">Souhlasím s <a target = "__blank" href="./PDF/obchod.pdf">obchodními podmínkami</a></label>
        </div>
        <button type="button" class="submit-btn">Objednat</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="./JS/userDetailts.js"></script>

<?php
    require "./pages/footer.php";
?>

</body>
</html>
