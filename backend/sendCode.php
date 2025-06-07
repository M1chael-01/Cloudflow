<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// require "./vendor/PHPMailer/src/Exception.php";
require "../../vendor/PHPMailer/src/Exception.php";
// require "../vendor/";
require "../../vendor/PHPMailer/src/PHPMailer.php";
require "../../vendor/PHPMailer/src/SMTP.php";

//session_start(); // Make sure to start the session

if(isset($_SESSION["user-code"])) {
    class SendCode {
        public static function sendCode($user_email, $code) {
            $mail = new PHPMailer(true);
            try {
                $mail->SMTPDebug = 0;  // Disable debug output
                $mail->isSMTP();  // Use SMTP
                $mail->Host = 'smtp.gmail.com';  // SMTP server
                $mail->SMTPAuth = true;  // Enable SMTP authentication
                $mail->Username = 'cloudflowinf@gmail.com';  // SMTP username
                $mail->Password = 'cqsr alpu jlxm obvh';  // SMTP password (use an App Password for security)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  // Use SSL encryption
                $mail->Port = 465;  // Port for SSL encryption

                // Recipients
                $mail->setFrom('cloudflowinf@gmail.com', 'Cloudflow info');  // Sender email
                $mail->addAddress($user_email);  // Send email to the user
                $mail->addReplyTo($user_email);  // Set reply-to to user's email
                $mail->addReplyTo("cloudflowinf@gmail.com");  // Set reply-to to this email

                // Content
                $mail->isHTML(true);  // Set email format to HTML
                $mail->CharSet = 'UTF-8';  // Set UTF-8 encoding
                $mail->Subject = 'Cloudflow.s.r.o - Zapomenuté heslo';  // Subject

                // The body of the email
                $mail->Body = "
                <html>
                <head>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            background-color: #f4f4f4;
                            margin: 0;
                            padding: 20px;
                        }
                        .container {
                            background-color: #ffffff;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                        }
                        h2 {
                            color: #333;
                        }
                        .code {
                            font-size: 20px;
                            font-weight: bold;
                            color: #4CAF50;
                            background-color: #f1f1f1;
                            padding: 10px;
                            border-radius: 4px;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 14px;
                            color: #888;
                        }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <h2>Zapomenuté heslo - Cloudflow.s.r.o</h2>
                        <p>Váš ověřovací kód je: <span class='code'>$code</span></p>
                        <p>Prosím použijte tento kód pro obnovení vašeho hesla.</p>
                        <div class='footer'>
                            <p>Pokud jste tuto žádost neprovedli, ignorujte tento e-mail.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";

                // Send email
                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }

}
