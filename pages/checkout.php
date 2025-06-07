<!-- used image:
 https://www.freepik.com/free-vector/all-right-emoji-illustration_158832963.htm#fromView=search&page=2&position=3&uuid=71bf6278-2994-4491-ba15-91b0ec8db271&query=emoji -->

 <?php
  require "./pages/routing.php";
require_once "./backend/checkCookie.php";
if(!CheckCookie::checkCookie() && !isset($_GET["uvod"]))  {echo "<script>location.href = '?uvod'</script>";}
?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "./backend/adminOrder.php";

require "./vendor/PHPMailer/src/Exception.php";
require "./vendor/PHPMailer/src/PHPMailer.php";
require "./vendor/PHPMailer/src/SMTP.php";
// Retrieve user details from the session
$user_first_name = isset($_SESSION['user_details']['first_name']) ? $_SESSION['user_details']['first_name'] : '';
$user_last_name = isset($_SESSION['user_details']['last_name']) ? $_SESSION['user_details']['last_name'] : '';
$user_email = isset($_SESSION['user_details']['email']) ? $_SESSION['user_details']['email'] : '';
$user_phone = isset($_SESSION['user_details']['phone']) ? $_SESSION['user_details']['phone'] : '';

// Retrieve delivery address from session
$address = isset($_SESSION["deliveryInfo"]) ? $_SESSION["deliveryInfo"]["street"] . ", " . $_SESSION["deliveryInfo"]["city"] : '';
$deliveryId = isset($_POST['deliveryInfo']['deliveryId']) ? htmlspecialchars($_POST['deliveryInfo']['deliveryId']) : '';
$deliveryComp = isset($_SESSION['deliveryInfo']['deliveryComp']) ? htmlspecialchars($_SESSION['deliveryInfo']['deliveryComp']) : ''; 
$price = isset($_SESSION['deliveryInfo']['price']) ? htmlspecialchars($_SESSION['deliveryInfo']['price']) : ''; 

// Check if the necessary session data is set before proceeding
if (isset($_SESSION["user_details"]) && isset($_SESSION["deliveryInfo"]) && isset($_SESSION["products"])) {

    // Calculate total price of the order
    $total_price = 0;
    foreach ($_SESSION['products'] as $product) {
        $total_price += $product['price'] * $product['quantity'];
    }
    // +50 = pay in cash
    $total_price = $total_price+$price+50;     // no VAT

    // $vat = $total_price/100*  1.21;
    $withVat = $total_price*1.21;   // with VAT  
    $vat = $withVat-$total_price; // clean VAT

SendOrder::sendOrder($_SESSION['products'], $_SESSION['deliveryInfo'], $_SESSION['user_details'], $total_price);


    // Initialize PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->SMTPDebug = 0;  // Disable debug output
        $mail->isSMTP();  // Use SMTP
        $mail->Host = 'smtp.gmail.com';  // SMTP server
        $mail->SMTPAuth = true;  // Enable SMTP authentication
        $mail->Username = 'cloudflowinf@gmail.com';  // SMTP username
        $mail->Password = 'cqsr alpu jlxm obvh';  // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // Use SSL encryption
        $mail->Port = 465;  // Port for SSL encryption
        // Recipients
        if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {  // check if is valid emal
            $mail->setFrom('cloudflowinf@gmail.com', 'Vaše objednávka');  // Sender email
            $mail->addAddress($user_email);  // Send email to the user
            $mail->addReplyTo($user_email);  // Set reply-to to user's email
        } 

        // Optionally add CC if email is valid, copy
        if (isset($_POST["email"]) && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $mail->addCC($_POST["email"]);
        }

        // Content
        $mail->isHTML(true);  // Set email format to HTML
        $mail->CharSet = 'UTF-8';  // Set UTF-8 encoding

        // Email subject
        $mail->Subject = 'Potvrzení objednávky - Faktura';

        // HTML email body content
        $mail->Body = '
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .invoice { border: 1px solid #ddd; padding: 20px; margin: 20px; }
                    .invoice h3 { margin-top: 0; }
                    .details p { margin: 5px 0; }
                    .products { list-style-type: none; padding-left: 0; }
                    .products li { margin-bottom: 10px; }
                    .product { display: flex; align-items: center; }
                    .product img { margin-right: 10px; }
                </style>
            </head>
            <body>
                <h2>Potvrzení objednávky</h2>
                <div class="invoice">
                    <h3>Vaše údaje:</h3>
                    <div class="details">
                        <p><strong>Jméno:</strong> ' . htmlspecialchars($user_first_name . ' ' . $user_last_name) . '</p>
                        <p><strong>Email:</strong> ' . htmlspecialchars($user_email) . '</p>
                        <p><strong>Telefon:</strong> ' . htmlspecialchars($user_phone) . '</p>
                        <p><strong>Adresa:</strong> ' . htmlspecialchars($address) . '</p>
                         <p><strong>Platba a doprava:</strong> ' . htmlentities($deliveryComp) . ', dobírkou (' . htmlentities($price+50) . ',- CZK)</p>
                    </div>
                    <hr>
                    <h3>Produkty:</h3>
                    <ul class="products">';
        
        // Loop through products and display each product
        foreach ($_SESSION['products'] as $product) {
            $mail->Body .= '
                <li>
                    <div class="product">
                        <img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '" width="50">
                        <p>' . htmlspecialchars($product['quantity']) . 'x ' . htmlspecialchars($product['name']) . '</p>
                        <p>Cena: ' . number_format($product['price'], 2, ',', ' ') . ' Kč</p>
                    </div>
                </li>';
        }

        // Close product list
        $mail->Body .= '</ul>';

        $mail->Body .= '
            <hr>
            <h3>Faktura:</h3>
            <p><strong>Celková částka bez DPH:</strong> ' . number_format($total_price, 2, ',', ' ') . ' CZK</p>
            <p><strong>Celková částka s DPH:</strong> ' . number_format( $withVat, 2, ',', ' ') . ' CZK</p>
            <p><strong>DPH:</strong> ' . number_format(round($vat), 0, ',', ' ') . ' CZK</p>
        </div>
        <p>Prosím, zkontrolujte svou emailovou schránku pro potvrzení objednávky a fakturu.</p>
        </body>
        </html>';

        // Send the email
        $mail->send();

        // Set flag to indicate email was sent
        $_SESSION['email_sent'] = true;

        // Call the function to remove order info from session
        removeOrderInfo();
    } catch (Exception $e) {
        echo "Chyba při odesílání emailu: {$mail->ErrorInfo}";
    }
}

// Function to clear order info
function removeOrderInfo() {
    // Remove session data related to the order
    unset($_SESSION['user_details']);
    unset($_SESSION['deliveryInfo']);
    unset($_SESSION['products']);
}

?>
<!-- used:image
 https://www.freepik.com/free-vector/thumbs-up-outline-sticker-overlay-with-white-border-magenta-pink-background_28428314.htm#fromView=search&page=2&position=23&uuid=f82a0d5c-d728-40d8-85c5-da2b4497c758&new_detail=true&query=thumb+up%C2%A8 -->

 <head>
    <title>Potvrzení objednávky</title>
</head>
<body>

<div id="confirmation-container">
    <h1>Objednávka byla úspěšně odeslána!</h1>
    
    <div class="confirmation-message">
        <p>Rádi bychom vám poděkovali za vaši objednávku. Vaše objednávka byla úspěšně zpracována.</p>
    </div>

    <!-- User Details -->
     <div class="flex">

   
    <div class="user-details">
        <h2>Vaše údaje:</h2>
        <p><strong>Jméno:</strong> <?php echo htmlspecialchars($user_first_name . ' ' . $user_last_name); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?></p>
        <p><strong>Telefon:</strong> <?php echo htmlspecialchars($user_phone); ?></p>
        <p><strong>Adresa:</strong> <?php echo htmlspecialchars($address); ?></p>
        <p><strong>Platba a doprava:</strong> <?php echo htmlentities($deliveryComp); ?>, dobírkou (<?php echo intval($price) + 50; ?>,- CZK)</p>
       
    </div>
    <div>
    <img src="./images/others/emoji.png" alt="">
    </div>
    </div>
    <!-- Email Confirmation -->
    <div class="email-info">
        <p>Potvrzení objednávky a faktura byla odeslána na váš email: <strong><?php echo htmlspecialchars($user_email); ?></strong>.</p>
        <p>Prosím, zkontrolujte svou emailovou schránku pro více informací.</p>
    </div>

    <!-- Back to Shop Button -->
    <div class="buttons">
    <a href="?obchod" class="back-btn">Pokračovat v nákupu</a>
    </div>
</div>

</body>
</html>
