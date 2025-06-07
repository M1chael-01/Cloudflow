<?php
 require "./pages/routing.php";
 
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>
<?php
// session_start(); // Always start the session at the top
ob_start();

// Check if product ID and "pridat" are set in the URL
if (isset($_GET["produkt"]) && isset($_GET["pridat"]) && !isset($_GET["added"])) {
    $json_data = file_get_contents('./data/products_services.json'); // Load JSON file
    $data = json_decode($json_data, true); // Decode JSON data into array
    $products = $data['products']; // Products list
    $product_id = $_GET["produkt"]; // Get the product ID from URL
    $quantity = isset($_GET["quantity"]) ? intval($_GET["quantity"]) : 1; // Get quantity from URL (default to 1)

    $product_found = null;

    // Find the product by ID
    foreach ($products as $product) {
        if ($product["id"] == $product_id) {
            $product_found = $product;
            break; 
        }
    }

    // If product is found, add it to the session
    if ($product_found) {
        // Initialize products session array if it's not already set
        if (!isset($_SESSION["products"])) {
            $_SESSION["products"] = [];
        }

        // Check if the product is already in the cart, and update its quantity if needed
        $product_in_cart = false;
        foreach ($_SESSION["products"] as &$existing_product) {
            if ($existing_product['id'] == $product_found['id']) {
                $existing_product['quantity'] += $quantity; // Update quantity
                $product_in_cart = true;
                break;
            }
        }
        // If product is not in the cart, add it
        if (!$product_in_cart) {
            $product_found['quantity'] = $quantity; // Set quantity for the product
            $_SESSION["products"][] = $product_found; // Add the product to the session
        }

        // After adding, redirect with "added=true" to prevent another refresh
        header("Location: ?produkt=$product_id");
        exit; // Stop further script execution after redirect
    } else {
        // Handle product not found error
        echo "Produkt nebyl nalezen.";
    }
}

ob_end_flush();
?>
<body>
    <style>
        .style{width: 200px;height:200px;background:#3d1a9a;position: relative;top:-200px;border-radius:150%;}
        #style2{position: relative;top:240px;left:-180px;}
    </style>

    <div class="container">
        <?php
        // Load JSON data from file
        $json_file = './data/products_services.json';
        $data = json_decode(file_get_contents($json_file), true);

        // Check if products data exists
        if (isset($data['products'])) {
            $products = $data['products'];

            // var_dump($products["image"]);

            // Get product ID from GET parameters
            $id = isset($_GET["produkt"]) ? $_GET["produkt"] : null;
            $product_found = false;

            // Search product by ID in products array
            foreach ($products as $product) {
                if ($product['id'] == $id) { 
                    $GLOBALS["id"] = $id;

                    // Show the product details
                    ?>
                    <div class="product-image">
                        <div id="arrow" onclick="goLeft();" style="position:relative;left:80px;" id="arrow-left">
                            <i class="ri-arrow-left-line"></i>
                        </div>
                        <img id="productImage" src="<?= ($product['image']) ?>"onerror="this.onerror=null; this.src='images/27002.jpg';" class="product-image" 
                             alt="<?= htmlspecialchars($product['name']) ?>">
                        <div id="arrow" onclick="goRight();" style="position:relative;left:-60px;" id="arrow-right">
                            <i class="ri-arrow-right-line"></i>
                        </div>
                    </div>

                    <div class="hidden" data-images="<?= htmlspecialchars(json_encode($product['images'])) ?>">

                    <div class="product-details">
                        <h1><?= htmlspecialchars($product['name']) ?></h1>
                        <p class="price"><?= number_format($product['price'], 2, ',', ' ') ?> Kč</p>

                        <?php
                        // Show a short description of the product
                        $short_description = mb_strimwidth(htmlspecialchars($product['more_info']), 0, 300, "...");
                        ?>
                        <p class="description"><strong>Popis:</strong> 
                            <span id="short-description"><?= $short_description ?></span>
                            <span id="full-description" style="display:none;"><?= htmlspecialchars($product['more_info']) ?></span>
                        </p>
                        <button id="show-more" onclick="toggleDescription()">Zobrazit více</button>

                        <?php if (isset($product['properties']) && !empty($product['properties'])) { ?>
                            <div class="properties">
                                <h3>Vlastnosti:</h3>
                                <dl>
                                    <?php foreach ($product['properties'] as $key => $value) { ?>
                                        <dt><?= htmlspecialchars($key) ?>:</dt>
                                        <dd><?= htmlspecialchars($value) ?></dd>
                                    <?php } ?>
                                </dl>
                            </div>
                        <?php } ?>

                        <div class="float">
                            <label for="quantity">Množství:</label>
                            <input id="quantity" class="quantity-input" type="number" value="1" min="1" max="10">
                        </div>
                        <a href="" id="btn" class="btn">Přidat do košíku</a>
                    </div>

                    <?php
                    $product_found = true;
                    break;
                }
            }

            // If product was not found
            if (!$product_found) { ?>
                <div class="error-message">Produkt s ID <?= htmlspecialchars($id) ?> nebyl nalezen.</div>
            <?php }
        } else { ?>
            <div class="error-message">Žádné produkty nejsou k dispozici.</div>
        <?php } ?>
    </div>

    <!-- Cart Count -->
    <div class="cart-count">
        <?php
        // Count the number of products in the cart (session)
        $cart_count = isset($_SESSION["products"]) ? count($_SESSION["products"]) : 0;
        ?>
    </div>
    <?php
    // Close output buffering and send output to the browser
    ob_end_flush();
    ?>
</body>

<script>
      // Toggle between short and full description
      function toggleDescription() {
        var fullDescription = document.getElementById('full-description');
        var shortDescription = document.getElementById('short-description');
        var showMoreButton = document.getElementById('show-more');

        if (fullDescription.style.display === "none") {
            fullDescription.style.display = "inline";
            shortDescription.style.display = "none";
            showMoreButton.innerHTML = "Zobrazit méně";
        } else {
            fullDescription.style.display = "none";
            shortDescription.style.display = "inline";
            showMoreButton.innerHTML = "Zobrazit více";
        }
    }

    // Add product to cart on button click
    document.getElementById('btn').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link action

        let quantity = document.getElementById('quantity').value; // Get quantity value
        let productId = "<?= $GLOBALS['id'] ?>"; // Get product ID from PHP

        // Create the new URL with the selected quantity
        let newUrl = "?produkt=" + productId + "&pridat=true&quantity=" + quantity;

        // Redirect to the new URL
        location.href = newUrl;
    });

   
 
</script>
<script src="./JS/buy.js"></script>
