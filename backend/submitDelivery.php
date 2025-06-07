<?php

// Define shipping methods
$shippingMethods = [
    ['id' => 1, 'logo' => '../images/shipping/cp.png', 'description' => 'Doporučení zásilka', 'price' => 100, 'date' => '1-2 pracovní dny'],
    ['id' => 2, 'logo' => '../images/shipping/ppl.png', 'description' => 'Expresní doručení', 'price' => 120, 'date' => '1 pracovní den'],
    ['id' => 3, 'logo' => '../images/shipping/dpd.png', 'description' => 'Doručení na adresu', 'price' => 130, 'date' => '2 pracovní dny'],
    ['id' => 5, 'logo' => '../images/shipping/gls.png', 'description' => 'GLS Doručení', 'price' => 110, 'date' => '2 pracovní dny'],
    ['id' => 6, 'logo' => '../images/shipping/dhl.png', 'description' => 'DHL Express', 'price' => 150, 'date' => '1-2 pracovní dny'],
];

// Check if POST request contains the required data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input data
    $deliveryId = isset($_POST['deliveryId']) ? (int)$_POST['deliveryId'] : 0;
    $city = isset($_POST['city']) ? htmlspecialchars($_POST['city']) : '';
    $state = isset($_POST['state']) ? htmlspecialchars($_POST['state']) : '';
    $postal_code = isset($_POST['postal_code']) ? htmlspecialchars($_POST['postal_code']) : '';
    $street = isset($_POST['street']) ? htmlspecialchars($_POST['street']) : '';
    $deliveryComp = isset($_POST['deliveryComp']) ? htmlspecialchars($_POST['deliveryComp']) : '';
    $deliveryId = isset($_POST['deliveryId']) ? htmlspecialchars($_POST['deliveryId']) : '';
    $price = isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '';

    // Start session to store delivery information
    session_start();
    
    // Store the sanitized data in session
    $_SESSION['deliveryInfo'] = [
        'deliveryId' => $deliveryId,
        'city' => $city,
        'state' => $state,
        'postal_code' => $postal_code,
        'street' => $street,
        'deliveryComp' => $deliveryComp,
        'price' => $price,
    ];

    // Debugging: Output session data to ensure it's stored correctly
    var_dump($_SESSION['deliveryInfo']);

    // Respond back to frontend with success
    echo json_encode(['success' => true, 'message' => 'Delivery information submitted successfully.']);
} else {
    // Respond with an error if no POST data is received
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
