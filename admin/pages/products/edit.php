<?php
require "./admin/pages/sitebar.php";
// Load the product data from the JSON file
$productFile = './data/products_services.json';
$products = json_decode(file_get_contents($productFile), true)['products'];

// Function to save updated products to the JSON file
function saveProducts($products) {
    global $productFile;
    file_put_contents($productFile, json_encode(['products' => $products], JSON_PRETTY_PRINT));
}

// Function to generate a unique image name if the file already exists
function generateUniqueImageName($filePath) {
    $pathInfo = pathinfo($filePath); //The pathinfo() function returns information about a file path.
    $extension = $pathInfo['extension'];
    $filenameWithoutExtension = $pathInfo['filename'];
    $newFilePath = $filePath;

    // If the file already exists, create a unique copy ID
    $counter = 1;
    while (file_exists($newFilePath)) {
        $newFileName = $filenameWithoutExtension . '_copy' . $counter . '.' . $extension; // renamae an image if the selected image name already exist
        $newFilePath = $pathInfo['dirname'] . '/' . $newFileName;
        $counter++;
    }
    return $newFilePath;
}

// Check if 'editID' is set in the URL and fetch the corresponding product
if (isset($_GET["editID"])) {
    $editID = (int)$_GET["editID"];
    
    $productToEdit = null;
    foreach ($products as $product) {
        if ($product['id'] == $editID) {
            $productToEdit = $product;
            break;
        }
    }

    if ($productToEdit === null) {
        echo "Produkt nebyl nalezen";
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
        $updatedProduct = $_POST['product'];
        $updatedProduct['id'] = $productToEdit['id'];

        // Handle main image upload
        if (!empty($_FILES['main_image']['name'])) {
            $imageName = basename($_FILES['main_image']['name']);
            $imageTmpName = $_FILES['main_image']['tmp_name'];
            $imageFilePath = './images/products/' . $imageName;
            
            // Check if the file exists and generate a unique name if necessary
            $imageFilePath = generateUniqueImageName($imageFilePath);

            if (is_uploaded_file($imageTmpName) && move_uploaded_file($imageTmpName, $imageFilePath)) {
                // Delete old image if exists
                if (!empty($productToEdit['image']) && file_exists($productToEdit['image'])) {
                    unlink($productToEdit['image']);
                }
                $updatedProduct['image'] = $imageFilePath;
            } else {
                // If upload fails, keep existing image
                $updatedProduct['image'] = $productToEdit['image'];
            }
        } else {
            // Keep existing image if no new image was uploaded
            $updatedProduct['image'] = $productToEdit['image'];
        }

        // Handle additional images upload
        if (!empty($_FILES['additional_images']['name'][0])) {
            $updatedProduct['images'] = [];
            foreach ($_FILES['additional_images']['name'] as $index => $name) {
                $tmpName = $_FILES['additional_images']['tmp_name'][$index];
                $fileName = basename($name);
                $filePath = './images/products/' . $fileName;

                // Check if the file exists and generate a unique name if necessary
                $filePath = generateUniqueImageName($filePath);

                if (is_uploaded_file($tmpName) && move_uploaded_file($tmpName, $filePath)) {
                    $updatedProduct['images'][] = $filePath;
                }
            }
        } else {
            // If no additional images were uploaded, keep existing ones
            $updatedProduct['images'] = $productToEdit['images'];
        }

        // Handle properties (assuming the properties array is part of the 'product' array in the form)
        if (!empty($updatedProduct['properties'])) {
            // Ensure properties are properly merged or updated
            $productToEdit['properties'] = $updatedProduct['properties'];
        }

        // Update other product details (name, description, etc.)
        $productToEdit = array_merge($productToEdit, $updatedProduct);

        // Update product in the products array
        foreach ($products as &$product) {
            if ($product['id'] == $editID) {
                $product = $productToEdit;
                break;
            }
        }

        // Save changes to the products JSON file
        saveProducts($products);

        // Redirect to the products page after update
        header("Location: ?produkty-admin");
        exit;
    }
} else {
    echo "Invalid product ID.";
    exit;
}
?>



<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="admin/styles/edit.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Úprava Produktu - Produkt #<?php echo $productToEdit['id']; ?></h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="d-flex mb-3">
            <!-- Name and Price on the same row -->
            <div class="form-group flex-fill mr-3">
                <label for="name" class="form-label">Název produktu</label>
                <input type="text" name="product[name]" id="name" class="form-control" value="<?php echo htmlspecialchars($productToEdit['name']); ?>" required>
            </div>
            <div class="form-group flex-fill">
                <label for="price" class="form-label">Cena</label>
                <input type="number" name="product[price]" id="price" class="form-control" value="<?php echo htmlspecialchars($productToEdit['price']); ?>" required>
            </div>
        </div>

        <div class="d-flex mb-3">
            <!-- Popisek and Popis produktu on the same row -->
            <div class="form-group flex-fill mr-3">
                <label for="description" class="form-label">Popisek</label>
                <input type="text" name="product[description]" id="description" class="form-control" value="<?php echo htmlspecialchars($productToEdit['description']); ?>" required>
            </div>
            <div class="form-group flex-fill">
                <label for="more_info" class="form-label">Popis produktu</label>
                <textarea name="product[more_info]" id="more_info" rows="4" class="form-control" required><?php echo htmlspecialchars($productToEdit['more_info']); ?></textarea>
            </div>
        </div>

        <!-- Button to show the second part -->
        <button type="button" class="btn btn-primary" id="show-second-part">Zobrazit obrázky</button>

        <!-- Second part with Properties and Images -->
        <div id="second-part">
            <!-- Main Image -->
            <div class="mb-3">
                <label for="main_image" class="form-label">Hlavní obrázek produktu</label>
                <input type="file" name="main_image" class="form-control" accept="image/*">
                <?php if (!empty($productToEdit['image'])): ?>
                    <img src="<?php echo './' . htmlspecialchars($productToEdit['image']); ?>" alt="Product Image" class="product-images">
                <?php endif; ?>
            </div>

            <!-- Additional Images -->
            <div class="mb-3">
                <label for="additional_images" class="form-label">Další obrázky</label>
                <input type="file" name="additional_images[]" class="form-control" accept="image/*" multiple>
                <?php if (!empty($productToEdit['images'])): ?>
                    <div class="product-images">
                        <?php
                        // Ensure that $productToEdit['images'] is an array before iterating
                        $images = is_array($productToEdit['images']) ? $productToEdit['images'] : explode(',', $productToEdit['images']);
                        foreach ($images as $image): ?>
                            <img src="<?php echo './' . htmlspecialchars($image); ?>" alt="Additional Image">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" name="update_product" class="btn btn-success">Aktualizovat produkt</button>
        </div>
        </div>
    </form>
</div>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const fileInputs = document.querySelectorAll("input[type='file']");
        fileInputs.forEach(input => {
            input.addEventListener("change", function() {
                if (this.files.length > 0) {}
            });
            document.getElementById('show-second-part').addEventListener('click', function() {
        const secondPart = document.getElementById('second-part');
        secondPart.style.display ="block";
    });
        });
    });
</script>
</body>
</html>
