<?php
session_start();

// Check if the product key is set in the request
if (isset($_POST['product_key']) && isset($_SESSION['products'])) {
    $product_key = (int)$_POST['product_key'];

    // Remove the product from the session products array
    if (isset($_SESSION['products'][$product_key])) {
        unset($_SESSION['products'][$product_key]);

        // Re-index the session array to avoid gaps in the keys
        $_SESSION['products'] = array_values($_SESSION['products']);

        // Respond with success and the new total quantity
        $totalQuantity = 0;
        foreach ($_SESSION['products'] as $product) {
            $totalQuantity += $product['quantity'];
        }

        echo json_encode(['success' => true, 'newTotalQuantity' => $totalQuantity]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
