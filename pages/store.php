<?php

require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>

<?php
//Preventing Early Output: PHP sends output to the browser as soon as the echo or print statements are called.
ob_start(); 

// Check if the session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start the session if it's not started
}

// Load data from JSON file
$json_data = file_get_contents('./data/products_services.json');
$data = json_decode($json_data, true);
$products = $data['products'];

// Number of products per page
$products_per_page = 12;

// Get the current page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $products_per_page;

// Get unique categories
$categories = [];
foreach ($products as $product) {
    $categories[] = $product['category'];
}
$categories = array_unique($categories);

// Filter products by price, name, and category
if (isset($_GET['filter_min']) && is_numeric($_GET['filter_min'])) {
    $price_min = (float)$_GET['filter_min'];
    $products = array_filter($products, function($product) use ($price_min) {
        return $product['price'] >= $price_min;
    });
}

if (isset($_GET['filter_max']) && is_numeric($_GET['filter_max'])) {
    $price_max = (float)$_GET['filter_max'];
    $products = array_filter($products, function($product) use ($price_max) {
        return $product['price'] <= $price_max;
    });
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = strtolower($_GET['search']);
    $products = array_filter($products, function($product) use ($search_term) {
        return strpos(strtolower($product['name']), $search_term) !== false;
    });
}

if (isset($_GET['category']) && $_GET['category'] !== '') {
    $selected_category = $_GET['category'];
    $products = array_filter($products, function($product) use ($selected_category) {
        return $product['category'] === $selected_category;
    });
}

// Products to display for the current page
$products_to_display = array_slice($products, $start, $products_per_page);

// Total number of products
$total_products = count($products);

// Total number of pages
$total_pages = ceil($total_products / $products_per_page);

// Add product to cart logic
if (isset($_GET['obchod']) && isset($_GET['kosik'])) {
    $product_id = $_GET['kosik'];
    $product_found = null;
    
    // Find the product from the list of all products
    foreach ($products as $product) {
        if ($product["id"] == $product_id) {
            $product_found = $product;
            break; // Exit loop once the product is found
        }
    }
    
    if ($product_found) {
        // Initialize the products session array if it's not already set
        if (!isset($_SESSION["products"])) {
            $_SESSION["products"] = [];
        }

        // Check if the product is already in the cart
        $product_in_cart = false;
        foreach ($_SESSION["products"] as &$cart_item) {
            if ($cart_item["id"] == $product_found["id"]) {
                // If the product is in the cart, increase its quantity by 1
                $cart_item["quantity"] += 1;
                $product_in_cart = true;
                break;
            }
        }

        // If the product wasn't found in the cart, add it with quantity 1
        if (!$product_in_cart) {
            $product_found["quantity"] = 1; // Set the initial quantity to 1
            $_SESSION["products"][] = $product_found;
        }
    }
    //refresh page
    echo "<script>location.href = '?obchod';</script>";
}


?>

<head>
    <title>Obchod</title>
</head>

<aside class="sidebar-filter">
    <div class="filter-container">
        <h3 class="filter-title"> Zjednodušte <br> si hledání </h3>
        <form action="" method="GET" class="filter-form">
            <!-- Price filter -->
            <div class="filter-group">
                <label for="price_min">Cena od:</label>
                <input type="number" name="filter_min" id="price_min" placeholder="Např. 100" value="<?php echo isset($_GET['filter_min']) ? $_GET['filter_min'] : ''; ?>">
            </div>
            <div class="filter-group">
                <label for="price_max">Cena do:</label>
                <input type="number" name="filter_max" id="price_max" placeholder="Např. 1000" value="<?php echo isset($_GET['filter_max']) ? $_GET['filter_max'] : ''; ?>">
            </div>
            <!-- Search by product name --> 
            <div class="filter-group">
                <label for="search">Vyhledat:</label>
                <input type="text" name="search" id="search" placeholder="Hledat produkty..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            </div>
            <!-- Category filter -->
            <div class="filter-group">
                <label for="category">Kategorie:</label>
                <select name="category" id="category">
                    <option value="">Všechny kategorie</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category; ?>" <?= isset($_GET['category']) && $_GET['category'] == $category ? 'selected' : ''; ?>>
                            <?php echo $category; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="button-group">
                <button type="submit" class="btn-filter">Filtrovat</button>
                <button type="button" class="btn-reset" onclick="resetFilters()">Reset</button>
            </div>
        </form>
    </div>
</aside>
<main id="products">
    <div class="products-container">
        <?php if (count($products_to_display) > 0): ?>
            <?php foreach ($products_to_display as $product): ?>
                <div class="product-card" onclick="window.location.href = '?produkt=<?php echo $product['id']; ?>'">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" onerror="this.onerror=null; this.src='images/27002.jpg';" class="product-image">

                    <div class="product-info">
                        <h2><?php echo $product['name']; ?></h2>
                        <p class="product-description"><?php echo $product['description']; ?></p>
                        <p class="product-price"><?php echo number_format($product['price'], 2, ',', ' '); ?> Kč</p>
                        <p class="product-category"><strong>Kategorie:</strong> <?php echo $product['category']; ?></p>
                        <a href="?obchod&kosik=<?php echo $product['id']; ?>" class="buy-button">Koupit</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Žádné produkty k zobrazení.</p>
        <?php endif; ?>
    </div>
      <?php
?>

</main>
<!-- Pagination -->
<div class="pagination-container">
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?obchod&page=<?php echo $page - 1; ?>" class="prev">Předchozí</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?obchod&page=<?php echo $i; ?>" class="page-number <?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?obchod&page=<?php echo $page + 1; ?>" class="next">Další</a>
            <?php endif; ?>
        </div>
    </div>

    <?php
// Close output buffering and send the output to the browser
// ------------------------------------------------------------

// The ob_end_flush() function is used to flush (send) the content that was stored in the output buffer to the browser.
// If you previously used ob_start(), all output (like HTML, text, or data) generated by the script is stored in a buffer.
// By calling ob_end_flush(), you're telling PHP to send everything in the buffer to the browser in one go.

// Why I use this?
// 1. If there was any previous output generated that you didn't want to send immediately (for example, HTML or data),
//    this function ensures that all content is sent at the end of the script execution.
// 2. Output buffering can also be used to modify the output or manage headers more effectively before anything is sent to the browser.

// In summary, ob_end_flush() ensures that everything in the output buffer is flushed and sent to the browser in the correct order.
ob_end_flush();
?>


<script>
    function resetFilters() {
    window.location.href = window.location.pathname + "?obchod";   
}
</script>

</body>
</html>
