<?php

// i use this condition cause if i didnt and i try to make an order I'd show me an error like the file are importnat dont't exist 
if (!isset($_POST["sendReq"]))  {
    require "./pages/routing.php";
    require_once "./backend/checkCookie.php";
    if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
}


?>
<head>
<style>
    .server {background: url("./images/services/server.jpg") no-repeat center center/cover;}
    .del-user-order-info {
        float: right;
       margin-top: -6vh;
    }
        .del-user-order-info i{
            font-size: 35px;
            cursor: pointer;
        }
</style>

<title>Serverová Řešení</title>
</head>

<body>

<?php


// Database connection function
function orders() {
    $db_host = "127.0.0.1"; // Database host
    $db_user = "root"; // Database user
    $db_password = ""; // Database password
    $db_name = "cloudflow_orders"; // Database name
    
    $connection = mysqli_connect($db_host, $db_user, $db_password, $db_name);
    
    if (mysqli_connect_error()) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    
    return $connection;
}

// Check if the 'sendReq' parameter is set
if (isset($_POST["sendReq"])) {
    // Decode JSON input
    $userOrder = json_decode($_POST['userOrder'], true);

    // Log received data for debugging
    // file_put_contents("debug_log.txt", print_r($userOrder, true), FILE_APPEND);

    // Validate required fields
    if (isset($userOrder['services']) && isset($userOrder['delivery']) && isset($userOrder['billing'])) {

        // Prepare goods/services, delivery, and billing data
        $goods_services = $userOrder['services'];
        $delivery = $userOrder['delivery'];
        $billing = $userOrder['billing'];
        $accepted = "no"; // Set default value for accepted field

        // Debugging output
        echo "<pre>";
        echo "Goods Services: " . print_r($goods_services, true) . "\n";
        echo "Delivery: " . print_r($delivery, true) . "\n";
        echo "Billing: " . print_r($billing, true) . "\n";
        echo $billing["email"];  // Make sure $billing is an array and not a JSON string
        echo "</pre>";

        // Establish database connection
        $conn = orders();

        if ($conn) {
            echo "Database connection successful.\n";

            // Ensure autocommit is enabled
            mysqli_autocommit($conn, true);

            // Prepare SQL statement to include 'accepted' field
            $sql = "INSERT INTO `one_order` (`goods_services`, `delivery`, `billing`, `date`, `accepted`) VALUES (?, ?, ?, NOW(), ?)";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                // Bind parameters (added 'accepted' parameter)
                mysqli_stmt_bind_param($stmt, "ssss", json_encode($goods_services), json_encode($delivery), json_encode($billing), $accepted);

                // Execute statement and check if successful
                if (mysqli_stmt_execute($stmt)) {
                    echo "✅ Order inserted successfully.\n";
                } else {
                    echo "❌ Error inserting order: " . mysqli_error($conn) . "\n";
                    return;
                }

                // Close statement
                mysqli_stmt_close($stmt);
            } else {
                echo "❌ Error preparing SQL statement: " . mysqli_error($conn) . "\n";
            }
            
            require "../../backend/sendInvoiceService.php";

            // Send the invoice after order insertion
            // No need to json_encode() here as these are already arrays
            SendInvoiceService::SendInvoiceService($billing['email'], $goods_services, $delivery, $billing);

            // Close the database connection
            mysqli_close($conn);

        } else {
            echo "❌ Failed to connect to the database.\n";
            exit;
        }

    } else {
        echo "❌ Invalid order data. Please check the submitted information.\n";
    }
} else {
    echo "❌ Form not submitted properly.\n";
}
?>

   <section class="server">
        <main>
            <h2 id="hading">Serverová řešení pro malé i velký projekty</h2>
             <p>Umožníme vaší firmě bez problémů provozovat náročné
                 projekty s vysokými požadavky na výkon a zároveň 
                 zajistíme stabilní provoz menších specifických potřeb
                  s individuálním řešením. </p>
            <a href="#contact-form" class="cta-button">Kontaktujte nás pro objednání</a>
        </main>
    </section>
    <?php  require "./pages/services/query.php"; ?>
      
    <section id="contact-form">
        
    <!-- Step 1: Product & Service Selection -->
    <div class="contact-form" id="step-1"  stateID = "1" >
        
    <h3>Objednejte serverová řešení</h3>
        <!-- Progress Bar (Inside Step 1) -->
        <div class="progress-bar">
            <span id="progress"></span>
        </div>
     
        <!-- <form action="#" method="POST"> -->
            <div class="form-group">
                <label>Vyberte produkt a služby</label>
                <select name="storage-size" id="storage-size" onchange="updatePrice()" required>
                        <option value="" disabled selected>Vyberte velikost uložiště</option>
                        <option value="500GB">500 GB</option>
                        <option value="1TB">1 TB</option>
                        <option value="2TB">2 TB</option>
                        <option value="4TB">4 TB</option>
                        <option value="5TB">5 TB</option>
                        <option value="6TB">6 TB</option>
                        <option value="8TB">8 TB</option>
                        <option value="10TB">10 TB</option>
                        <option value="own">Vlastní velikost</option>
                    </select>

                <label>Vyberte službu:</label>
                <div class="checkbox-group">
                    <input type="checkbox" name="managed-services" id="managed-services" value="server-management" onchange="updatePrice()">
                    <label for="managed-services">Nastavení serverů</label>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="managed-services" id="tuning-services" value="server-tuning" onchange="updatePrice()">
                    <label for="tuning-services">Ladění serveru</label>
                </div>
            </div>

            <div class="total-price">
                Celková cena: <span id="total-price">1000 Kč</span>
            </div>
            <button id="btn" type="button" onclick="nextStep(1)">Pokračovat</button>
        </form>
    </div>

    <!-- Step 2 (Hidden Initially) -->
    <div class="contact-form" id="step-2" stateID = "2" style="display: none;">
        <!-- Progress Bar (Inside Step 2) -->
        <div class="progress-bar" id="order-form">
            <span id="progress"></span>
        </div>
        <h3>Vyplňte kontaktní údaje</h3>
        <form action="#" method="POST">
            <div class="form-group">
                <div class="names">
                    <input type="text" name="full-name" id="full-name" placeholder="Vaše jméno a příjmení" required>
                    <input type="email" name="email" id="email" placeholder="Vaše emailová adresa" required>
                </div>
                <div class="names">
                <input type="tel" id="phone" placeholder="Vaše telefoní číslo" required>
                <textarea name="notes" id="notes" placeholder="Poznámky k objednávce" rows="5"></textarea>
                </div>
               
            </div>
            <button type="button" onclick="nextStep(2)">Pokračovat</button>
        </form>
    </div>

    <!-- Step 3 (Hidden Initially) -->
    <div class="contact-form" id="step-3"  stateID = "3"  style="display: none;">
        <!-- Progress Bar (Inside Step 3) -->
        <div class="progress-bar">
            <span id="progress"></span>
        </div>
        <h3>Vaše doručovací adresa</h3>  
        <form id="formOrder" >
            <div class="form-group">
                <input type="text" name="city" id="city" placeholder="Město" required>
                <input type="text" name="postal-code" id="postal-code" placeholder="PSČ" required>
                <input type="text" name="street-name" id="street-name" placeholder="Název ulice" required>
            </div>
            <div class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10882.646185183386!2d15.594736954175618!3d49.40711417309868!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470d1a433857ab8f%3A0x400af0f66152a00!2sJihlava%2C%20586%2001%20Jihlava%201!5e0!3m2!1scs!2scz!4v1741172249427!5m2!1scs!2scz" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <button  type="submit">Odeslat objednávku</button>
        </form>
    </div>
</section>

<?php 
    require "./pages/footer.php"
?>
<script src="./JS/clean.js"></script>
<script src="./JS/server.js"></script>
</body>
</html>

