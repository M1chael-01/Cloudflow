<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "../vendor/PHPMailer/src/Exception.php";
require "../vendor/PHPMailer/src/PHPMailer.php";
require "../vendor/PHPMailer/src/SMTP.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_email = $_POST["email"];
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

          // Content
          $mail->isHTML(true);  // Set email format to HTML
          $mail->CharSet = 'UTF-8';  // Set UTF-8 encoding
          $mail->Subject = 'Cloudflow.s.r.o - Zeptejte se nás vše co potřebujete';
 // HTML Email Template
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
         .container {
             max-width: 600px;
             margin: 20px auto;
             background: #ffffff;
             padding: 20px;
             border-radius: 8px;
             box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
         }
         .header {
             background: #0073e6;
             color: #ffffff;
             text-align: center;
             padding: 10px;
             font-size: 24px;
             border-radius: 8px 8px 0 0;
         }
         .content {
             padding: 20px;
             text-align: center;
         }
         .button {
             display: inline-block;
             background: #0073e6;
             color: #ffffff;
             text-decoration: none;
             padding: 10px 20px;
             border-radius: 5px;
             font-weight: bold;
             margin-top: 10px;
         }
         .footer {
             text-align: center;
             padding: 10px;
             font-size: 12px;
             color: #777;
         }
     </style>
 </head>
 <body>
     <div class="container">
         <div class="header">Cloudflow Info</div>
         <div class="content">
             <p>Dobrý den,</p>
             <p>Děkujeme, že jste nás kontaktovali.Právě teď s nás můžete zeptat na co chcete</p>
             <p>Napište nám váš dotaz a mi vám co nejrychleji odpovíme.</p>
             <a href="mailto:cloudflowinf@gmail.com" class="button">Kontaktovat nás</a>
         </div>
         <div class="footer">
             &copy; ' . date('Y') . ' Cloudflow.s.r.o | Všechna práva vyhrazena.
         </div>
     </div>
 </body>
 </html>
 ';
   // Send Email
   if ($mail->send()) {
    echo "Email sent successfully!";
} else {
    echo "Email sending failed.";
}

    }
    catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        echo "Email could not be sent. Please try again later.";
    }

}

