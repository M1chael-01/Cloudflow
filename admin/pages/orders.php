<?php
require "./admin/pages/sitebar.php";
require "./database/administration/orders.php";
require "./backend/emailOrderInfo.php";
$conn = orders();  // Database connection
// Function to fetch all order IDs
function selectIDS() {
    global $conn;
    $sql = "SELECT id FROM one_order WHERE accepted = 'no'  ORDER BY id ASC  ";
    $result = $conn->query($sql);

    $ids = [];
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row['id'];
    }
    return $ids;
}
// Fetch all order IDs
$ids = selectIDS();
$firstOrder = !empty($ids) ? $ids[0] : null;

// Get order ID from GET request, default to first available order
$orderID = isset($_GET['id']) ? intval($_GET['id']) : $firstOrder;


// If there are no orders, stop execution
if (!$orderID) {
    echo "<script>window.location.href = '?zadnaObjednavka';</script>";
    exit();
}

// Query to fetch the order by ID
$sql = "SELECT * FROM one_order WHERE id = $orderID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $goods_services = json_decode($row['goods_services'], true);
    $delivery = json_decode($row['delivery'], true);
    $billing = json_decode($row['billing'], true);
} else {
    echo "<script>window.location.href = '?zadnaObjednavka';</script>";
    exit();
}

// Get the next order ID
$index = array_search($orderID, $ids);
$nextID = ($index !== false && isset($ids[$index + 1])) ? $ids[$index + 1] : null;
$preID = ($index !== false && isset($ids[$index -1])) ? $ids[$index -1] : null;

// Count total orders dynamically
$ordersCount = count($ids);

// Debugging (optional)
function calculateVAT($price) {
    return round(($price / 100) * 21, 0);  // 21% VAT
}

if (isset($_GET["conf"]) && isset($_GET["id"])) {
    $id = intval($_GET["id"]);  // Sanitize the ID (assuming it's an integer)
    $email = isset($billing["email"]) ? $billing["email"] : null;  // Ensure $email is set from $billing array

    // Use prepared statements to avoid SQL injection
    global $conn;

    // Prepare the SQL query
    $sql = "UPDATE one_order SET accepted = 'yes' WHERE id = ?";
    
    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameter
        $stmt->bind_param("i", $id);  // 'i' indicates that $id is an integer
        // Execute the query
        $stmt->execute();
        // Close the prepared statement
        $stmt->close();
        EmailOrderInfo::emailOrderInfo($email);;
        header("Location: ?objednavky-admin&id=" . $nextID);
    } else {
        // Handle error if the statement preparation fails
        echo "Error: Could not prepare the query.";
    }
}

function selectLastID() {
    global $conn;

    // Query to select the penultimate record ID
    $query = "SELECT id FROM one_order ORDER BY id DESC LIMIT 1 OFFSET 1";

    // Execute the query
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        // Fetch the penultimate record's ID
        $row = mysqli_fetch_assoc($result);
        return $row['id'];
    } else {
        // Handle the error if query fails
        echo "Error: " . mysqli_error($conn);
        return null;
    }
}

?>  
<head>
    <title>Vaše objednávky</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin/styles/orders.css">
</head>

<body>

    <div class="container">
        <div class="order-card">
            <h2>ID Objednavky: <?php echo $row["id"]; ?></h2>
            <div class="content-wrapper">
                <div class="text-content">
                    <div class="section">
                        <div class="info-wrapper">
                            <div class="section odb">
                                <h3>Odběratel:</h3>
                                <p><strong>Jméno:</strong> <?php echo isset($billing['first_name']) ? $billing['first_name'] : 'N/A'; ?></p>
                                <p><strong>Příjmení:</strong> <?php echo isset($billing['last_name']) ? $billing['last_name'] : 'N/A'; ?></p>

                                <p><strong>E-mail:</strong> <?php echo isset($billing['email']) ? $billing['email'] : 'N/A'; ?></p>
                                <p><strong>Telefon:</strong> <?php echo isset($billing['phone']) ? $billing['phone'] : 'N/A'; ?></p>
                            </div>
                            <div class="section delivery">
                                <h3>Informace o dodání:</h3>
                                <p><strong>Adresa</strong> <?php echo isset($delivery['street']) ? $delivery['street'] : 'N/A'; ?>, <?php echo isset($delivery['city']) ? $delivery['city'] : 'N/A'; ?>, <?php echo isset($delivery['state']) ? $delivery['state'] : 'N/A'; ?>, <?php echo isset($delivery['postal_code']) ? $delivery['postal_code'] : 'N/A'; ?></p>
                                <p><strong>Delivery Company:</strong> <?php echo isset($delivery['deliveryComp']) ? $delivery['deliveryComp'] : 'Cloudflow s.r.o'; ?></p>
                                <p><strong>Cena dodání:</strong> <?php echo isset($delivery['price']) ? $delivery['price'] : 'Bude určena'; ?> </p>
                            </div>
                        </div>
                        <div class="section" id="products">
                            <h3>Co obsahuje objednávka</h3>
                            <?php
                            $total_price = 0;  
                            foreach ($goods_services as $product): 
                                $product_price = isset($product['price']) ? $product['price'] : 0;
                                $quantity = isset($product['quantity']) ? $product['quantity'] : 1;
                                $total_price += $product_price * $quantity;
                            ?>
                                <div class="product">
                                    <h4><?php echo isset($product['name']) ? $product['name'] : 'Product Name'; ?></h4>
                                    <p><strong>Cena:</strong> <?php echo $product_price; ?> CZK</p>
                                    <p><strong>Množství:</strong> <?php echo $quantity; ?></p>
                                </div>
                            <?php endforeach; ?>
                            <div class="section">
                                <h3>Celková cena:</h3>
                                <p><strong>Celkově s DPH:</strong> <?php echo $total_price + calculateVAT($total_price); ?> CZK</p>
                                <p><strong>Celkově bez DPH:</strong> <?php echo $total_price; ?> CZK</p>
                                <p><strong>Cena DPH:</strong> <?= calculateVAT($total_price) ?> CZK</p>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>

            <div class="section action-buttons" id="control">
 <?php (isset($_GET["id"])) ? $url = "?objednavky-admin&conf=true&id=" . $_GET["id"]:$url = "?objednavky-admin&conf=true&id=".$firstOrder; ?>
<a href=<?=$url?>><button id="confirm" class="btn btn-confirm">Přijmout</button></a>
                <a id="link-comf" href="<?php echo $nextID ? "?objednavky-admin&id=$nextID" : '#'; ?>">
                    <button class="btn btn-confirm" <?php echo !$nextID ? 'disabled' : ''; ?>>Další</button>
                </a>
                <a href="<?php echo $nextID ? "?objednavky-admin&id=$preID" : '?objednavky-admin&id=' . selectLastID(); ?>">
                    <button class="btn btn-confirm" <?php echo !$preID ? 'disabled' : ''; ?>>Předchozí</button>
                </a>
            </div>
        </div>
    </div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
