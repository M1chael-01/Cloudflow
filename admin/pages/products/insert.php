<?php
require "./admin/pages/sitebar.php";
$productFile = './data/products_services.json';
$products = json_decode(file_get_contents($productFile), true)['products'];

// Function to save updated products to JSON file
function saveProducts($products) {
    global $productFile;
    file_put_contents($productFile, json_encode(['products' => $products], JSON_PRETTY_PRINT));  // insert into json file
}

// Function to generate a unique image name if the file already exists
function generateUniqueImageName($filePath) {
    $pathInfo = pathinfo($filePath);
    $extension = $pathInfo['extension'];
    $filenameWithoutExtension = $pathInfo['filename'];
    $newFilePath = $filePath;

    // If the file already exists, create a unique copy ID
    $counter = 1;
    while (file_exists($newFilePath)) {
        $newFileName = $filenameWithoutExtension . '_copy' . $counter . '.' . $extension;
        $newFilePath = $pathInfo['dirname'] . '/' . $newFileName;
        $counter++;
    }
    return $newFilePath;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $newProduct = $_POST['product'];

    // Create a new ID for the new product (incrementing the highest existing ID)
    $newProduct['id'] = !empty($products) ? max(array_column($products, 'id')) + 1 : 1;

    // Handle product properties (store them in a name => value format)
    if (isset($_POST['properties']['property_name']) && isset($_POST['properties']['property_value'])) {
        $newProduct['properties'] = [];
        foreach ($_POST['properties']['property_name'] as $key => $propertyName) {
            // Ensure both property name and value are present before adding them
            if (!empty($propertyName) && isset($_POST['properties']['property_value'][$key])) {
                $newProduct['properties'][$propertyName] = $_POST['properties']['property_value'][$key];
            }
        }
    } else {
        $newProduct['properties'] = [];
    }

    // Handle the main product image
    if (isset($_FILES['product']['name']['main_image'])) {
        $imageName = $_FILES['product']['name']['main_image'];
        $imageTmpName = $_FILES['product']['tmp_name']['main_image'];
        $imageFilePath = './images/products/' . basename($imageName);

        // Check if the file already exists and generate a unique name if necessary
        $imageFilePath = generateUniqueImageName($imageFilePath);

        move_uploaded_file($imageTmpName, $imageFilePath);
        $newProduct['image'] = $imageFilePath;
    }

    // Handle additional images
    $newProduct['images'] = [];
    if (isset($_FILES['product']['name']['additional_images'])) {
        foreach ($_FILES['product']['name']['additional_images'] as $index => $name) {
            $tmpName = $_FILES['product']['tmp_name']['additional_images'][$index];
            $fileName = basename($name);
            $filePath = './images/products/' . $fileName;

            // Check if the file already exists and generate a unique name if necessary
            $filePath = generateUniqueImageName($filePath);
            move_uploaded_file($tmpName, $filePath);
            $newProduct['images'][] = $filePath; // Add image path to product images array
        }
    }
    // Add the new product to the products array
    $products[] = $newProduct;
    // Save the updated product list
    saveProducts($products);
    // Redirect to the products page after saving
    header("Location: ?produkty-admin");
    exit;
}
?>
<head>
    <title>Přidat nový produkt</title>
    <link rel="stylesheet" href="admin/styles/insert.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Přidat nový produkt</h2>

    <form method="POST" class="faq-form" enctype="multipart/form-data">
        
        <div class="faq-entry">
            <div class="faq-header">
                <div class="d-flex align-items-center">
                    <h4>Přidání nového produktu</h4>
                </div>
            </div>
            
            <div class="input-group mb-3">
                <div class="form-group flex-fill">
                    <label for="name">Název produktu</label>
                    <input type="text" name="product[name]" id="name" class="form-control" required>
                </div>

                <div class="form-group flex-fill">
                    <label for="price">Cena</label>
                    <input type="number" name="product[price]" id="price" class="form-control" required>
                </div>
            </div>

            <div class="d-flex mb-3">
                <div class="form-group flex-fill mr-3">
                    <label for="category">Kategorie</label>
                    <input type="text" name="product[category]" id="category" class="form-control" required>
                </div>

                <div class="form-group flex-fill">
                    <label for="description">Popisek</label>
                    <input type="text" name="product[description]" id="more_info" class="form-control" required>
                </div>
            </div>

            <div class="form-group flex-fill mb-3">
                <label for="more_info">Popis produktu</label>
                <textarea name="product[more_info]" id="description" rows="4" class="form-control" required></textarea>
            </div>

            <button type="button" class="btn btn-primary" id="show-second-part">Zobrazit další vlastnosti a obrázky</button>

            <div id="second-part">
                <label for="">Vlastnosti</label>
                <div id="properties">
                    <div class="property-row" id="property-1">
                        <label for="property_name_1">Název vlastnosti</label>
                        <input type="text" name="properties[property_name][]" id="property_name_1" class="form-control">
                    </div>
                    <div class="property-row" id="property-1">
                        <label for="property_value_1">Hodnota vlastnosti</label>
                        <input type="text" name="properties[property_value][]" id="property_value_1" class="form-control">
                    </div>
                </div>
                <button type="button" id="add-property" class="btn btn-secondary">Přidat vlastnost</button>

                <label for="main_image">Hlavní obrázek produktu</label>
                <div id="main-image-preview" class="mb-3">
                    <input type="file" id="main-image-upload" name="product[main_image]" accept="image/*" class="form-control" required>
                    <div id="main-image-preview-container"></div>
                </div>

                <label for="additional_images">Další obrázky produktu</label>
                <div id="additional-images-preview" class="mb-3">
                    <input type="file" id="additional-images-upload" name="product[additional_images][]" accept="image/*" class="form-control" multiple>
                    <div id="additional-images-preview-container"></div>
                </div>
            </div>
        </div>

        <button type="submit" name="add_product" class="btn btn-success mt-3">Přidat produkt</button>
    </form>

    <div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3">
        <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
            <div class="toast-body">
                <strong>Produkt byl úspěšně přidán.</strong>
            </div>
        </div>
    </div>
</div>

<script>
    // Handle main image preview
    document.getElementById('main-image-upload').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('main-image-preview-container');
        previewContainer.innerHTML = '';
        const files = event.target.files;
        
        if (files.length) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.margin = '5px';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(files[0]); // Only one file for the main image
        }
    });

    // Handle additional images preview
    document.getElementById('additional-images-upload').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('additional-images-preview-container');
        previewContainer.innerHTML = '';
        const files = event.target.files;

        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.margin = '5px';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(files[i]);
        }
    });

    // Toggle visibility of second part of the form
    document.getElementById('show-second-part').addEventListener('click', function() {
        const secondPart = document.getElementById('second-part');
        secondPart.style.display = secondPart.style.display === 'none' || secondPart.style.display === '' ? 'block' : 'none';
    });

    // Add more product properties dynamically
    document.getElementById('add-property').addEventListener('click', function() {
        const properties = document.getElementById('properties');
        const propertyIndex = properties.children.length / 2 + 1; // To create a new pair of property name and value inputs
        const propertyRow = ` 
            <div class="property-row" id="property-${propertyIndex}">
                <label for="property_name_${propertyIndex}">Název vlastnosti</label>
                <input type="text" name="properties[property_name][]" id="property_name_${propertyIndex}" class="form-control">
            </div>
            <div class="property-row" id="property-${propertyIndex}">
                <label for="property_value_${propertyIndex}">Hodnota vlastnosti</label>
                <input type="text" name="properties[property_value][]" id="property_value_${propertyIndex}" class="form-control">
            </div>
        `;
        properties.insertAdjacentHTML('beforeend', propertyRow);
    });

    // Display success message (toast)
    <?php if (isset($_POST['add_product'])) { ?>
        const toast = new bootstrap.Toast(document.getElementById('toast'));
        toast.show();
    <?php } ?>
</script>
</body>
</html>