<?php
require "./pages/routing.php";

$shippingMethods = [
    ['id' => 1, 'logo' => './images/shipping/cp.png', 'description' => 'Doporučení zásilka', 'price' => 100, 'date' => '1-2 pracovní dny'],
    ['id' => 2, 'logo' => './images/shipping/ppl.png', 'description' => 'Expresní doručení', 'price' => 120, 'date' => '1 pracovní den'],
    ['id' => 3, 'logo' => './images/shipping/dpd.png', 'description' => 'Doručení na adresu', 'price' => 130, 'date' => '2 pracovní dny'],
    
    // Added new shipping companies
    ['id' => 5, 'logo' => './images/shipping/gls.png', 'description' => 'GLS Doručení', 'price' => 110, 'date' => '2 pracovní dny'],
    ['id' => 6, 'logo' => './images/shipping/dhl.png', 'description' => 'DHL Express', 'price' => 150, 'date' => '1-2 pracovní dny'],
];
// pay in cash
$paymentMethods = [
    ['id' => 1, 'name' => 'Hotově (Dobírka)', 'fee' => 50],
];
// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    if (empty($_POST['shipping_method'])) $errors[] = 'Vyberte způsob dopravy.';
    if (empty($_POST['payment_method'])) $errors[] = 'Vyberte způsob platby.';
    if (empty($_POST['city'])) $errors[] = 'Zadejte město.';
    if (empty($_POST['street'])) $errors[] = 'Zadejte ulici.';
    if (empty($_POST['state'])) $errors[] = 'Vyberte stát.';
    if (empty($_POST['postal_code'])) $errors[] = 'Zadejte PSČ.';

    if (empty($errors)) {
        $_SESSION['shipping'] = [
            'method' => $shippingMethods[(int)$_POST['shipping_method'] - 1],
        ];
        $_SESSION['payment'] = [
            'method' => $paymentMethods[(int)$_POST['payment_method'] - 1],
        ];
        $_SESSION['address'] = [
            'city' => htmlspecialchars(trim($_POST['city'])),
            'street' => htmlspecialchars(trim($_POST['street'])),
            'state' => htmlspecialchars(trim($_POST['state'])),
            'postal_code' => htmlspecialchars(trim($_POST['postal_code'])),
        ];
        exit;
    }
}
?>

<?php
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>


<head>
    <title>Doprava a Platba</title>
</head>

<body>

<div id="form-container">
      <!-- Step Indicator -->
      <div class="step-indicator">
        <div class="step">1 NÁKUPNÍ KOŠÍK</div>
        <div class="step active">2 DOPRAVA A PLATBA</div>
        <div class="step">3 VAŠE ÚDAJE</div>
    </div>
  

    <!-- Display Errors -->
    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <label>Zvolte způsob dopravy:</label>
        <div class="shipping-options">
            <?php foreach ($shippingMethods as $method): ?>
                <label class="shipping-method">
                    <div class="shipping-info">
                        <img src="<?= $method['logo']; ?>" alt="<?= htmlspecialchars($method['description']); ?>" class="shipping-logo">
                        <div class="shipping-details">
                            <input class="shipping" type="text"  value="" hidden>    <!-- Hidden input for shipping method value -->
                            <p><?= htmlspecialchars($method['description']); ?></p>
                            <p class="shipping-price"><?= number_format($method['price'], 2); ?> Kč</p>
                            <p class="shipping-date"><?= htmlspecialchars($method['date']); ?></p>
                        </div>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Payment Methods -->
    <div class="form-group">
        <label for="payment_method">Zvolte způsob platby:</label>
        <select name="payment_method" id="payment_method" required>
            <option value="1">Hotově (Dobírka) - Příplatek 50.00 Kč</option>
        </select>
    </div>

    <!-- Address in Two Rows -->
    <div class="form-group row">
        <!-- First Row: City and Country -->
        <div class="input-container">
            <label for="city">Město:</label>
            <input type="text" id="city" required placeholder="Např. Praha">
        </div>

        <div class="input-container">
            <label for="state">Stát:</label>
            <select  id="state" required>
                <option value="cz">Česká republika</option>
                <option value="sk">Slovensko</option>
                <option value="de">Německo</option>
                <option value="pl">Polsko</option>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <!-- Second Row: Postal Code and Address -->
        <div class="input-container">
            <label for="postal_code">PSČ:</label>
            <input type="text"  id="postal_code" required placeholder="Např. 110 00">
        </div>

        <div class="input-container">
            <label for="street">Ulice:</label>
            <input type="text" id="street" required placeholder="Např. Ulice 123">
        </div>
    </div>

    <button type="submit" class="submit-btn">Pokračovat</button>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="./JS/shipping_payment.js"></script>

<?php
    require "./pages/footer.php";
?>


</body>
</html>
