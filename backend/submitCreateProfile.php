<?php
// Get POST data
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$account = $_POST['account'];
$team = isset($_POST['team']) ? $_POST['team'] : '';

$response = array(
    'name' => $name,
    'email' => $email,
    'account' => $account,
    'team' => $team
);

// Send a JSON response back to the client
echo json_encode($response);
?>
