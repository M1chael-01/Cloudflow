
<?php
// Start output buffering to prevent headers already sent error
ob_start(); // Call this first!

if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Start the session if it's not already started
}
?>

<?php
// Load products from a JSON file (this is just for illustration purposes)
$file_content = file_get_contents('./data/products_services.json');
if ($file_content === false) {
    die("Error reading products file.");
}
$products = json_decode($file_content, true); // Assuming products.json is in the same directory

// Check if the 'obchod' and 'kosik' parameters are set
if (isset($_GET['obchod']) && isset($_GET['kosik'])) {
    $product_id = $_GET['kosik'];

    // Check if the session "products" array is already initialized
    if (!isset($_SESSION["products"])) {
        $_SESSION["products"] = array(); // Initialize the products array if not already set
    }

    // Find the product with the corresponding ID
    $product_found = null;
    foreach ($products as $product) {
        if ($product["id"] == $product_id) {
            $product_found = $product;
            break; // Exit loop once the product is found
        }
    }

    // If the product is found, add it to the session products array
    if ($product_found) {
        $_SESSION["products"][] = $product_found;
    }
}
?>
<head>
    <link rel="stylesheet" href="./styles/others/header.css">
    <link rel="stylesheet" href="./styles/others/cart.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
</head>
<body>
<?php
// in this case a user cannot see a header
if (isset($_GET["adminLogged"]) || isset($_GET["aplikace-admin"]) || isset($_GET["faq-admin"]) || isset($_GET["produkty-admin"]) || isset($_GET["objednavky-admin"]) || isset($_GET["statistiky-admin"]) || isset($_GET["editID"]) || isset($_GET["createID"]) || isset($_GET["reklamace-admin"]) ||isset($_GET["zadnaObjednavka"]) || isset($_GET["zadnyUzivatel"]) ) {
    $GLOBALS["show"] = false;
}
    else{
        $GLOBALS["show"] = true;
    }
?>

<?php if ($GLOBALS["show"]): ?>
    <header>
        <nav>
            <a id = "logo-text" class="logo" href="?uvod">Cloud<span>Flow</span></a>
            <ul>
                <li><a id="5" href="?bezpeci">Bezpečnostní služby</a></li>
                <li><a id="3" href="?server">Server na Míru</a></li>
                <li><a id="4" href="?aplikace">Cloudová Aplikace</a></li>
                <li><a id="5" href="?obchod">Obchod</a></li>
                <li><a id="6" href="?kontakty">Kontakty</a></li>
            </ul>
            <div class="ul" id="menu" onclick="showUl()"><i class="ri-menu-line"></i></div>
           
</div>
            <a onclick="showMenu()" href="#" id="cart" class="btn">
            <span class="number">
                <?php 
                    $totalQuantity = 0;
                    if (isset($_SESSION["products"])) {
                        foreach ($_SESSION["products"] as $product) {
                            $totalQuantity += $product['quantity']; // Sum the quantity of each product
                        }
                    }
                    echo $totalQuantity;
                ?>
            </span>
            <i id="icon" class="ri-shopping-cart-line"></i>Košík</a>
            <a href="?prihlaseni" id="login" class="btn"><i id="icon" class="ri-contacts-line"></i>Přihlášení</a>
        </nav>
    </header>
<?php else: ?>
    <style>
        header {
            display: none;
        }
    </style>
<?php endif; ?>

<div class="cart-modal" id="cartModal">
    <div class="cart-content <?php echo empty($_SESSION['products']) ? 'empty' : ''; ?>">
        <div class="remove"><i onclick="hideMenu()" class="ri-close-line"></i></div>
  
        <h2>Košík</h2>
        
        <?php if (!isset($_SESSION["products"]) || empty($_SESSION['products'])): ?>
            <div class="cart-icon">
                <i class="ri-shopping-cart-line"></i>
            </div>
            <p>Košík je prázdný</p>
        <?php else: ?>
            <ul class="products">
                <?php foreach ($_SESSION['products'] as $key => $product): ?>
                    <li>
                        <div class="product">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="50">
                            
                            <!-- Display quantity of the product -->
                            <p style="margin: 0 10px 0 0;"><?php echo $product['quantity']; ?>x</p> <!-- Show actual quantity -->

                            <p><?php echo $product['name']; ?></p>
                            <p>Price: <?php echo number_format($product['price'], 2, ',', ' ') ?> Kč</p>

                            <!-- Form to remove the product -->
                            <form action="remove_product.php" method="POST">
                                <!-- DOESN'T EXIST YET -->
                                <input type="hidden" name="product_key" value="<?php echo $key; ?>" /> 
                                <button type="submit" class="remove-btn"><i class="ri-close-line"></i></button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <a href="?obchod" class="continue-btn">Pokračovat v nákupu</a>
        <?php
        if(!empty($_SESSION["products"])) : ?>
        <a id="order" href="?objednavka=<?= date('Y-m-d') ?>" class="continue-btn">Dokončit objednávku</a>
        <?php endif; ?>
    </div>
</div>

<script src="./JS/cart.js"></script>
<script src="./JS/header.js"></script>


</body>
</html>

<?php
// End output buffering and flush the output to the browser
ob_end_flush();
?>