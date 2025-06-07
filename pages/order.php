<?php
 require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>


<?php
// load a file
require "./backend/checkOrder.php";

if (session_status() == PHP_SESSION_NONE) {
session_start();
}
// Initialize session array for products if not set
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [];
}
// Add a product to the session for demonstration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_name'], $_POST['product_price'], $_POST['product_image'])) {
    // Check if the product already exists in the session, and increase quantity if it does
    $product = [
        'name' => $_POST['product_name'],
        'price' => $_POST['product_price'],
        'image' => $_POST['product_image'],
        'quantity' => 1 // Default quantity is 1
    ];
    $found = false;
    foreach ($_SESSION['products'] as &$existingProduct) {
        if ($existingProduct['name'] == $product['name']) {
            // Product found, increase the quantity
            $existingProduct['quantity']++;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $_SESSION['products'][] = $product; // Add new product if not found
    }
}
// Calculate total price
$totalPrice = 0;
foreach ($_SESSION['products'] as $product) {
    $totalPrice += $product['price'] * $product['quantity']; // Multiply price by quantity
}
if (!CheckOrder::checkOrder()) {
    if (isset($_SESSION["has_visited"])) {
        // User has visited before, so go back to the previous page
        echo "<script>window.history.back();</script>";
    } else {
        // User has not visited before, redirect to the home page
        // $_SESSION["has_visited"] = true; // Mark user as visited
        echo "<script>location.href = '?uvod';</script>";
    }
}
?>

<head>
    <title>Nákupní košík</title>
</head>

<body>

<div id="cart-container">
    <!-- Step Indicator -->
    <div class="step-indicator">
        <div class="step active">1 NÁKUPNÍ KOŠÍK</div>
        <div class="step">2 DOPRAVA A PLATBA</div>
        <div class="step">3 VAŠE ÚDAJE</div>
    </div>
    <!-- Cart Header -->
    <div class="cart-header">Produkt</div>
    <!-- Cart Items -->
    <div id="cart">
        <?php if (!empty($_SESSION['products'])): ?>
            <?php foreach ($_SESSION['products'] as $product): ?>
                <div class="cart-item">
                    <img src="<?= htmlspecialchars($product['image']); ?>" alt="Product">
            
                    <span><?= htmlspecialchars($product['name']); ?> (Množství: <?= $product['quantity']; ?>)</span>
                    <span><?= number_format($product['price'], 2); ?> Kč</span>
                    <span><strong><?= number_format($product['price'] * $product['quantity'], 2); ?> Kč</strong></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-cart">Váš košík je prázdný.</p>
        <?php endif; ?>
    </div>
    <?php
            $vat = ($totalPrice /100) * 21;
            $realPrice = number_format($vat + $totalPrice,2);
            // calc vat
            // calc price before 
            // sum it price+vat , add to digit number(desitá místa)
            // show it into the price 
        ?>
    <!-- Price Details -->
    <div class="price-details">
        <span>Cena celkem:<?=$totalPrice?></span>
        <span><?= $realPrice ?> Kč</span>
    </div>
    <div class="price-details">
        <span>21% DPH :<?= number_format($vat,0)?> Kč</span>
        <?= $realPrice?> Kč</span>
    </div>

    <!-- Continue Shopping Button -->
    <a href="?doprava&platba"><button class="continue-btn">Pokročovat k dopravě a platbě</button></a>
</div>

<?php
    require "./pages/footer.php";
?>

</body>
</html>
