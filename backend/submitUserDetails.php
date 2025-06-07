<?php
session_start();

// Check if all required data is sent via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $firstName = isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : '';
    $lastName = isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
    $notes = isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : '';

    // Store data in session (or save to a database)
    $_SESSION['user_details'] = [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'notes' => $notes
    ];

    // Send JSON response
    echo json_encode([
        'success' => true,
        'message' => 'User details submitted successfully!'
    ]);
} else {
    // Error response if the request is invalid
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.'
    ]);
}
?>
