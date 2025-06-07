<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "./vendor/PHPMailer/src/Exception.php";
require "./vendor/PHPMailer/src/PHPMailer.php";
require "./vendor/PHPMailer/src/SMTP.php";

class EmailOrderInfo {
    public static function emailOrderInfo($user_email) {
        $mail = new PHPMailer(true);
        try {
            // SMTP setup
            $mail->SMTPDebug = 0;  // Disable debug output
            $mail->isSMTP();  // Use SMTP
            $mail->Host = 'smtp.gmail.com';  // SMTP server
            $mail->SMTPAuth = true;  // Enable SMTP authentication
            $mail->Username = 'cloudflowinf@gmail.com';  // SMTP username
            $mail->Password = 'cqsr alpu jlxm obvh';  // SMTP password (consider using environment variables for better security)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // Use SSL encryption
            $mail->Port = 465;  // Port for SSL encryption

            // Set up the sender and recipient
            $mail->setFrom('cloudflowinf@gmail.com', 'Cloudflow info');
            $mail->addAddress($user_email);  // Recipient's email
            $mail->addReplyTo("cloudflowinf@gmail.com");

            // Email content
            $mail->isHTML(true);  // Set email format to HTML
            $mail->CharSet = 'UTF-8';  // Set UTF-8 encoding
            $mail->Subject = 'Cloudflow.s.r.o - Informace o vaší objednávce';

            // Email body with a nice UI/UX design
            $mail->Body = '
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f9;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: 30px auto;
                        background-color: #ffffff;
                        border-radius: 8px;
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                        padding: 20px;
                    }
                    h1 {
                        color: #4CAF50;
                        font-size: 24px;
                        margin-bottom: 20px;
                    }
                    p {
                        font-size: 16px;
                        line-height: 1.6;
                        color: #555555;
                    }
                    .highlight {
                        color: #4CAF50;
                        font-weight: bold;
                    }
                    .footer {
                        margin-top: 20px;
                        text-align: center;
                        font-size: 14px;
                        color: #777777;
                    }
                    .button {
                        display: inline-block;
                        padding: 10px 20px;
                        background-color: #4CAF50;
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 5px;
                        font-weight: bold;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>Vaše objednávka byla právě zpracována!</h1>
                    <p>Vážený zákazníku,</p>
                    <p>děkujeme za vaši objednávku. Vaše objednávka byla právě zpracována a očekávejte její doručení do <span class="highlight">3 dnů</span>.</p>
                    <p>Pokud máte jakékoliv otázky, neváhejte nás kontaktovat.</p>
                    <p>Hezký den!</p>
                    <p class="footer">Tým Cloudflow.s.r.o</p>
                </div>
            </body>
            </html>';

            // Send the email
            if ($mail->send()) {
                echo 'Email has been sent successfully.';
            } else {
                echo 'Error sending email.';
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>
