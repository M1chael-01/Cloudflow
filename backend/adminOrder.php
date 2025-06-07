<?php

// if(file_exists("./database/administration/orders.php")) {
require "./database/administration/orders.php";
// }


// Get the database connection
$dataOrder = orders(); // Assuming orders() function returns the DB connection

class SendOrder {
    public static function sendOrder($products, $delivery, $billing) {
        global $dataOrder;

        // Get current date and time for the order
        $orderDate = date("Y-m-d H:i:s");

        // Convert products, delivery, and billing to JSON format to store in the database
        $productsJson = json_encode($products, JSON_UNESCAPED_UNICODE);
        $deliveryJson = json_encode($delivery, JSON_UNESCAPED_UNICODE);
        $billingJson = json_encode($billing, JSON_UNESCAPED_UNICODE);

        // Default value for 'accepted' field
        $accepted = "no";

        // Prepare SQL query to insert the order into the database
        $sql = "INSERT INTO `one_order` (`goods_services`, `delivery`, `billing`, `date`, `accepted`) 
                VALUES (?, ?, ?, ?, ?)";

        // Prepare the SQL statement
        if ($stmt = $dataOrder->prepare($sql)) {
            // Bind the parameters
            $stmt->bind_param("sssss", $productsJson, $deliveryJson, $billingJson, $orderDate, $accepted);

            // Execute the query
            if ($stmt->execute()) {
                // Order inserted successfully
                $orderId = $stmt->insert_id; 
                return $orderId; // Return the order ID for further processing
            } else {
                // Handle SQL errors
                return false;
            }
        } else {
            // Statement preparation failed
            return false;
        }
    }
}

?>
