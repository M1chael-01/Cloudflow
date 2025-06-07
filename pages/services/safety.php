<?php
// i use this condition cause if i didnt and i try to make an order I'd show me an error like the file that are importnat dont't exist 
if (!isset($_POST["sendReq"]))  {
    require "./pages/routing.php";
    require_once "./backend/checkCookie.php";
    if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
}


?>
<head>
    <title>Objednávka Bezpečnostních Řešení</title>
    <script src="https://unpkg.com/scrollreveal"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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

    <!-- Background & Main Section -->
    <section class="security">
        <main>
            <h2 id="hading">Bezpečnostní Řešení pro Vaše Podnikání</h2>
            <p>Chraňte svou firmu s našimi pokročilými technologiemi. Zajistíme komplexní ochranu dat a neustálý dohled proti kyberhrozbám. Naše spolehlivé a efektivní řešení vám poskytne klid a zaručí dlouhodobou bezpečnost vašeho podnikání.</p>
            <a href="#web"><button>Zabezpečte své podnikání nyní</button></a>
        </main>
    </section>

   <?php  require "./pages/services/query.php"; ?>


 <!-- Order Form Section -->
 <section id="contact-form" >
        <div class="order-form contact-form" id="web">
            <h3>Objednávka Bezpečnostního Řešení</h3>

            <!-- Progress Bar -->
            <div class="progress-bar">
                <span id="progress-bar"></span>
            </div>

            <!-- Step 1: Security Type & Services -->
            <div class="step active" id="step-1" stateID = "1">
                <label id="name" for="security-type">Vyberte typ zabezpečení:</label>
                <select id="security-type" required onchange="updatePrice()">
        <option value="firewall">Firewall</option>
   
        <option value="detection">Intrusion Detection</option>
        <option value="encryption">Šifrování dat</option>
        <option value="privacy">Ochrana soukromí</option>
        <option value="audit">Bezpečnostní audit</option>
        <option value="backup">Zálohování dat</option>
    </select>

                <label>Služby:</label>
                <div class="checkbox-group">
                    <input type="checkbox" id="monitoring" onchange="updatePrice()">
                    <label for="monitoring">Nepřetržité monitorování</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="penetration-testing" onchange="updatePrice()">
                    <label for="penetration-testing">Penetrační testování</label>
                </div>

                <div class="total-price" id="total-price">Celková cena: 1000 CZK</div>

             

                <button type="button" onclick="nextStep(1)">Pokračovat</button>
            </div>

            <!-- Step 2: Personal Information -->
             
            <div class="step" id="step-2" stateID = "2">
                
                <div class="flex">
                        <!-- <label for="full-name">Jméno:</label> -->
                <input type="text" id="full-name" placeholder="Vaše jméno a přijmení" required>

<!-- <label for="email">Email:</label> -->
<input type="email" id="email" placeholder="Vaše emailová adresa" required>

<!-- <label for="notes">Poznámky:</label> -->

                </div>
                <div class="flex">
                <input type="tel" id="phone" placeholder="Vaše telefoní číslo" required>
                <textarea id="notes" rows="4" placeholder="Vaše poznámky"></textarea>
                </div>
               
               


                <button type="button" onclick="nextStep(2)">Pokračovat</button>
            </div>

            <!-- Step 3: Delivery Address -->
            <form action="#" class="step" id="step-3" stateID = "3" >
               <div class="flex">
                 <!-- <label for="city">Město:</label> -->
                 <input type="text" id="city" placeholder="Město" required>

<!-- <label for="postal-code">PSČ:</label> -->
<input type="text" id="postal-code" placeholder="PSČ" required>

<!-- <label for="street-name">Název ulice:</label> -->
<input type="text" id="street-name" placeholder="Název ulice" required>
               </div>

               <div class="map">
               <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d10882.646185183386!2d15.594736954175618!3d49.40711417309868!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470d1a433857ab8f%3A0x400af0f66152a00!2sJihlava%2C%20586%2001%20Jihlava%201!5e0!3m2!1scs!2scz!4v1741172249427!5m2!1scs!2scz" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
                <!-- <p><a href="#" target="_blank">Jak bude vypadat můj server?</a></p> -->
                <div class="terms-group">
                <!-- <input type="checkbox" id="terms" required=""> -->
                <!-- <label for="terms">Souhlasím se <a href="#">zpracováním osobních údajů</a></label> -->
                </div>
                <button  type="submit">Odeslat objednávku</button>
            </form>
            </div>
 <!-- </section> -->

           <!-- Step 2 (Hidden Initially) -->
 <!-- Step 2 (Hidden Initially) -->

</section>  
    

    <?php 
    require "./pages/footer.php"
?>

<script src="./JS/clean.js"></script>

<script src="./JS/safety.js"></script>