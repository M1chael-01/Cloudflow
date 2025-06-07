<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "../vendor/PHPMailer/src/Exception.php";
require "../vendor/PHPMailer/src/PHPMailer.php";
require "../vendor/PHPMailer/src/SMTP.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the email is provided in the POST request
    if (isset($_POST["email"]) && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $user_email = $_POST["email"];  // Get the user's email from the form submission
        $msg = $_POST["message"];
        
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;  // Disable debug output
            $mail->isSMTP();  // Use SMTP
            $mail->Host = 'smtp.gmail.com';  // SMTP server
            $mail->SMTPAuth = true;  // Enable SMTP authentication
            $mail->Username = 'cloudflowinf@gmail.com';  // SMTP username
            $mail->Password = 'cqsr alpu jlxm obvh';  // SMTP password (Consider using environment variables for better security)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // Use SSL encryption
            $mail->Port = 465;  // Port for SSL encryption

            // Recipients
            $mail->setFrom('cloudflowinf@gmail.com', 'Cloudflow info');  // Sender email
            $mail->addAddress($user_email);  // Send email to the user
            $mail->addReplyTo($user_email);  // Set reply-to to user's email
            $mail->addReplyTo("cloudflowinf@gmail.com");  // Set reply-to to user's email
            $mail->addCC("cloudflowinf@gmail.com");  // Set reply-to to user's email
            
         

            // Content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->CharSet = 'UTF-8';  // Set UTF-8 encoding

            $mail->Subject = 'Cloudflow.s.r.o - Váš dotaz';
            
            $mail->Body = '
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }
                    .email-container {
                        width: 100%;
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #ffffff;
                        padding: 20px;
                        border-radius: 10px;
                    }
                    .email-header {
                        text-align: center;
                        padding: 10px 0;
                        background-color: #4CAF50;
                        color: #ffffff;
                        border-radius: 10px;
                    }
                    .email-body {
                        font-size: 16px;
                        color: #333333;
                        line-height: 1.5;
                    }
                    .email-footer {
                        font-size: 14px;
                        text-align: center;
                        color: #888888;
                        padding: 10px;
                    }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <div class="email-header">
                        <h2>Cloudflow.s.r.o - Váš dotaz</h2>
                    </div>
                    <div class="email-body">
                        <p>Dobrý den,</p>
                        <p>Vaše zpráva <strong>' . htmlspecialchars($msg) . '</strong> byla úspěšně přijata. Děkujeme, že jste nás kontaktovali. Pokud máte jakékoliv další dotazy, neváhejte se na nás obrátit.</p>
                        <p>Naši kolegové se vám co nejdříve ozvou.</p>
                    </div>
                    <div class="email-footer">
                        <p>Cloudflow.s.r.o &copy; 2025 | <a href="https://www.cloudflow.com" style="color: #4CAF50;">www.cloudflow.com</a></p>
                    </div>
                </div>
            </body>
            </html>';
            

            // Send the email
            $mail->send();
            echo 'Email has been sent successfully.';
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Invalid email address provided.";
    }
}
?>
