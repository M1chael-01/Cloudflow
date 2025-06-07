<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "../../vendor/PHPMailer/src/Exception.php";
require "../../vendor/PHPMailer/src/PHPMailer.php";
require "../../vendor/PHPMailer/src/SMTP.php";



class SendInvoiceService {

    // Method to send the invoice to the user
    public static function SendInvoiceService($user_email, $services, $delivery, $billing) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP(); 
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true; 
            $mail->Username = 'cloudflowinf@gmail.com'; 
            $mail->Password = 'cqsr alpu jlxm obvh'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Sender email address
            $mail->setFrom('cloudflowinf@gmail.com', 'Cloudflow info');
            // Recipient email address
            $mail->addAddress($user_email);
            // Reply-to address
            $mail->addReplyTo("cloudflowinf@gmail.com");

            $mail->isHTML(true); // Set the email format to HTML
            $mail->CharSet = 'UTF-8'; // Set the character encoding to UTF-8

            // Subject of the email
            $mail->Subject = 'Cloudflow-vaše faktura';

            // Decoding the input data
            $decoded_billing = $billing;
            $decoded_delivery = $delivery;
            $decoded_services = $services;

            // Body of the email with HTML and inline CSS
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; color: #333; margin: 0; padding: 0; background: #f9f9f9; }
                        .container { width: 600px; margin: 0 auto; background: #fff; padding: 20px; border: 1px solid #ddd; }
                        h1 { color: #4CAF50; text-align: center; font-size: 28px; }
                        h2 { color: #333; font-size: 20px; }
                        p { font-size: 14px; line-height: 1.6; }
                        .address { margin-bottom: 10px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }
                        th { background-color: #f2f2f2; }
                        .total { font-size: 16px; font-weight: bold; text-align: right; }
                        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #777; }
                        .footer p { margin: 0; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <h1>Cloudflow faktura</h1>
                        <div class='address'>
                            <h2>Fakturační údaje:</h2>
                            <p><strong>Jméno a přijmení:</strong> {$decoded_billing['first_name']} {$decoded_billing['last_name']}</p>
                            <p><strong>Telefon:</strong> {$decoded_billing['phone']}</p>
                        </div>
                        <div class='address'>
                            <h2>Adresa:</h2>
                            <p><strong>Adresa:</strong> {$decoded_delivery['street']}, {$decoded_delivery['city']}, {$decoded_delivery['state']} {$decoded_delivery['pascal_code']}</p>
                        </div>
                        
                        <h2>Podrobnosti faktury:</h2>
                        <table>
                            <tr>
                                <th>Služba</th>
                                <th>Cena (CZK)</th>
                            </tr>";

            // Loop through the services and display them
            $total_excl_vat = 0;
            foreach ($decoded_services as $service_name => $service_data) {
                $price = $service_data['price'];
                $total_excl_vat += $price;
                $mail->Body .= "
                    <tr>
                        <td>{$service_data['name']}</td>
                        <td>{$price}</td>
                    </tr>";
            }

            // Calculate VAT and Total
            $vat_rate = 0.21;  // 21% VAT
            $vat_amount = $total_excl_vat * $vat_rate;
            $total_incl_vat = $total_excl_vat + $vat_amount;

            $mail->Body .= "
                            <tr>
                                <td colspan='2' style='border-top: 2px solid #ddd;'></td>
                            </tr>
                            <tr>
                                <td class='total' colspan='2'>Cena bez DPH: {$total_excl_vat} CZK</td>
                            </tr>
                            <tr>
                                <td class='total' colspan='2'>DPH (21%): {$vat_amount} CZK</td>
                            </tr>
                            <tr>
                                <td class='total' colspan='2'>Cena s DPH: {$total_incl_vat} CZK</td>
                            </tr>
                        </table>

                        <div class='footer'>
                            <p>Děkujeme za vaši objednávku<br>Cloudflow Team</p>
                            <p>Kontaktujte nás na: cloudflowinf@gmail.com</p>
                        </div>
                    </div>
                </body>
                </html>";

            // Send the email
            $mail->send();
            echo "Email has been sent.";
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>
