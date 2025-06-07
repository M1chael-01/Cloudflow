    <?php 
    require "./admin/pages/sitebar.php";

    // Load the product data from the JSON file
    $productFile = './data/products_services.json';
    $products = json_decode(file_get_contents($productFile), true)['products'];

    // Function to save updated products to JSON file
    function saveProducts($products) {
        global $productFile;
        // Encode the products array as a JSON string and save it to the specified file with pretty print formatting
        file_put_contents($productFile, json_encode(['products' => $products], JSON_PRETTY_PRINT));
    }

    // Handle form submission for adding a new product
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
        $newProduct = $_POST['product'];
        $newProduct['id'] = time(); // Assign unique ID based on timestamp
        $products[] = $newProduct;
        saveProducts($products);
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to prevent re-submission
        exit;
    }

    // Pagination setup
    $productsPerPage =11;
    $totalProducts = count($products);
    $totalPages = ceil($totalProducts / $productsPerPage);

    // Get current page from URL, default to 1
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $currentPage = max(1, $currentPage); // Ensure page number is at least 1

    // Check if 'ID' is set and calculate the correct starting index
    if (isset($_GET["ID"])) {
        $currentPage = (int)$_GET["ID"];  // Assume 'ID' is the page number
        $currentPage = max(1, min($currentPage, $totalPages));  // Ensure 'ID' is within valid range
    }

    // Calculate the starting index of the products for the current page
    $startIndex = ($currentPage - 1) * $productsPerPage;

    // Get the products for the current page
    $paginatedProducts = array_slice($products, $startIndex, $productsPerPage);
// Check if deleteID is set in the URL
// Check if deleteID is set in the URL

if (isset($_GET["deleteID"])) {
    global $productFile, $products;
    $deleteID = (int)$_GET["deleteID"];  // Ensure deleteID is an integer

    // Find the product by ID
    foreach ($products as $key => $product) {
        if ($product["id"] == $deleteID) {
            // Get the image file names associated with the product
            $imageFileName = basename($product["image"]);  // Get the base name of the main image file
            $imagesFileNames = array_map('basename', $product["images"]);  // Get the base names of all additional image files

            // Remove the product from the array using its key
            unset($products[$key]);

            // Re-index the array after removal (optional)
            $products = array_values($products);

            // Save the updated products back to the JSON file
            saveProducts($products);

            // Define the path where the images are stored on the server
            $imageDirectory = './images/products/';

            // Delete the main image if it exists
            $imagePath = $imageDirectory . $imageFileName;
            
            if (file_exists($imagePath)) {
                unlink($imagePath);  // Deletes the image file from the server
            }

            // Delete additional images if they exist
            foreach ($imagesFileNames as $additionalImage) {
                $imagePath = $imageDirectory . $additionalImage;
                if (file_exists($imagePath)) {
                    unlink($imagePath);  // Deletes the additional image file from the server
                }
            }
            // Redirect to the products admin page after deletion
            header("Location: ?produkty-admin&ID=" . (isset($_GET['ID']) ? $_GET['ID'] : 1));
            exit(); 
        }
    }
}    ?>

    <head>
        <title>Admin Panel -Produkty</title>
        <link rel="stylesheet" href="admin/styles/products.css">
    </head>
    <body>
    <div class="container">
        <div class="content">
            <!-- Display Products -->
            <div class="row">
                <div class="item">
                    <h2>Přidejte produkt</h2>
                    <a style="color:#000000; text-decoration:none;" href="?createID"><i id="plus" class="ri-add-line"></i></a>

                    <p>Vytvoř nový produkt na platformu, který bude mít širokou nabídku funkcí a skvélé vlastnosti.</p>
                    <div class="controls" id="first">
                        <a href="?createID"><button class="btn-create">Vytvořit </button></a>
                    </div>
                </div>
                <?php foreach ($paginatedProducts as $index => $product): ?>
                    <div class="item">
                        <div class="details">
                            <h2>#<?php echo $startIndex + $index + 1; ?> - <?php echo htmlspecialchars($product['name']); ?></h2>
                            <img src="<?php echo htmlentities($product["image"]) ?>" alt="">
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                        </div>
                        <div class="controls">
                            <a href="?editID=<?=$product["id"]?>"><button class="btn-warning"><i class="ri-edit-line"></i></button></a>
                            <a href="?produkty-admin&ID=<?=$currentPage?>&deleteID=<?php echo $product['id']; ?>"><button class="btn-danger"><i class="ri-delete-bin-7-line"></i></button></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <!-- Pagination Links -->
    <div class="pagination">
        <!-- Previous Button -->
        <?php if ($currentPage > 1): ?>
            <a id="pre" href="?produkty-admin&ID=<?php echo $currentPage - 1; ?>">Předchozí</a>
        <?php else: ?>
            <a id="pre" href="#" class="disabled">Předchozí</a>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php
        // Calculate the range of pages to display
        $pageRange = 5; // Number of pages to show
        $startPage = max(1, $currentPage - floor($pageRange / 2));
        $endPage = min($totalPages, $startPage + $pageRange - 1);

        // Adjust the startPage if there are fewer than 5 pages
        if ($endPage - $startPage < $pageRange - 1) {
            $startPage = max(1, $endPage - $pageRange + 1);
        }

        for ($page = $startPage; $page <= $endPage; $page++):
        ?>
            <a href="?produkty-admin&ID=<?php echo $page; ?>" class="<?php echo ($page == $currentPage) ? 'active' : ''; ?>">
                <?php echo $page; ?>
            </a>
        <?php endfor; ?>


        <?php if ($currentPage < $totalPages): ?>
            <a id="next" href="?produkty-admin&ID=<?php echo $currentPage + 1; ?>">Další</a>
        <?php else: ?>
            <a id="next" href="#" class="disabled">Další</a>
        <?php endif; ?>
    </div>


        </div>
    </div>

    <script>
          
        addEventListener("resize", (e) => {
            (innerWidth>2000)  ? document.querySelector(".container").style.maxWidth = innerWidth - 320 :  (innerWidth>2000)  ? document.querySelector(".container").style.maxWidth = innerWidth - 350 : 

})
        document.querySelector(".container").style.maxWidth = innerWidth - 250;
    </script>

    </body>
    </html>
