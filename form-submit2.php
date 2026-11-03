<?php

include("maildetails.php");
// Include PHPMailer classes manually
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Collect and sanitize form data
    $firstName = htmlspecialchars($_POST['first_name'] ?? '');
    $lastName = htmlspecialchars($_POST['last_name'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $messageContent = htmlspecialchars($_POST['message'] ?? '');
    $recaptcha_response = $_POST['captchaInput'];
    $captchfieldname=$_POST['captchafield'] ?? '';

 
    //if (empty($firstName) || empty($lastName) || empty($email) || empty($messageContent) || empty($recaptcha_response)) {
    if (empty($firstName) || empty($lastName) || empty($email) || empty($messageContent) || empty($recaptcha_response)) {
        http_response_code(400);
        exit("All required fields must be filled.");
    } 

    if($captchaCheckd) {
        if($captchfieldname!=''){
            $serverCode=$_SESSION[$captchfieldname];
        }else{
            $serverCode = $_SESSION['captcha_code'];
        }
        if ($recaptcha_response != $serverCode) {
            http_response_code(400);
            exit("Invalid captcha, try again!");
        }

        if($captchfieldname!=''){
            unset($_SESSION[$captchfieldname]);
        }else{
            unset($_SESSION['captcha_code']);
        }

        // $response = file_get_contents(
        //     "https://www.google.com/recaptcha/api/siteverify?secret="
        //     . $captchSecreateKey . "&response=" . $recaptcha_response
        // );
    
        // $responseKeys = json_decode($response, true);
    }
    
 
    // --- Get Client IP Address ---
    // if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    //     $ip = $_SERVER['HTTP_CLIENT_IP'];
    // } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    // } else {
    //     $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    // }
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    }

    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $ip = 'Unknown';
    }

    // --- Get approximate location ---
    $location = 'Unknown';
    $geo = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,regionName,city");
    if ($geo) {
        $geoData = json_decode($geo, true);
        if ($geoData['status'] === 'success') {
            $location = $geoData['city'] . ', ' . $geoData['regionName'] . ', ' . $geoData['country'];
        }
    }

    $page_link = $_POST['page_link'] ?? '';
    if($apiEnabled) {
        // --- API endpoint and token ---
        $apiUrl = $apiUrl."/api/info-form";
        $apiData = [
            "first_name" => $firstName,
            "last_name" => $lastName,
            "email" => $email,
            "phone_number" => $phone,
            "message" => $messageContent,
            "ip_address" => $ip,
            "location" => $location,
            "page_link" => $page_link
        ];
        
        // --- Send data to API via cURL ---
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-API-Secret: zeAJKWEuJahIHVTvBVwlym',
            'X-API-Token: 39ZYdEDYnuczCdtcNN93zycayr5pIErckwz0K4bEuw'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiData));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // --- Handle API Response ---
        if ($error) {
            http_response_code(500);
            exit("Failed to connect to API. Error: " . $error);
        } elseif ($httpCode < 200 || $httpCode >= 300) {
            http_response_code($httpCode);
            exit("API Error ($httpCode): " . $response);
        }
    }

    // --- Send email to Sales Team ---
    $mailSales = new PHPMailer(true);
    try {
        $mailSales->isSMTP();
        $mailSales->Host       = $host;
        $mailSales->SMTPAuth   = true;
        $mailSales->Username   = $username;
        $mailSales->Password   = $psw;
        $mailSales->SMTPSecure = $SMTPSecure;
        $mailSales->Port       = $port;

        $mailSales->addAddress($talktoreplyautomated); // Sales team
        if($env != 'testing') {
            $mailSales->addCC($cc1);
            $mailSales->addCC($talktocc);
        }

        $mailSales->isHTML(true);
        $mailSales->Subject = "New Contact Form Submission – Cypherox Technologies";
                $svg = '   <tr>
                    <td bgcolor="#f26e65" align="center" style="padding:25px 20px; color:#ffffff; font-size:22px; font-weight:bold;">
                      <a href="https://www.cypherox.com/" target="_blank" style="display:inline-block; margin-bottom:15px;">
                        <img src="https://cypherox.com/assets/img/email-logo.png" /> 
                      </a>
                      <div style="font-size:22px; line-height:28px; font-weight:bold;">New Contact Submission</div>
                    </td>
                  </tr>';
                   $mailSales->CharSet = 'UTF-8';

                $mailSales->Body = "
        <!DOCTYPE html>
        <html lang='en'>
        <head><meta charset='UTF-8'><title>New Contact Form Submission</title></head>
        <body style='font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0;'>
            <table width='100%' cellpadding='0' cellspacing='0'>
                <tr>
                    <td align='center' style='padding:30px 15px;'>
                        <table width='600' style='background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,0.05);'>
                           {$svg}
                            <tr>
                                <td style='padding:30px 25px; color:#868686; font-size:16px; line-height:1.6;'>
                                    <h4 style='margin-top:0; color:#2a2a2a;'>Hello Team,</h4>
                                    <p>A new contact form submission has been received through the website.</p>
                                    <p><strong>Enclosed are the client details:</strong></p>
                                    <p><strong>Name:</strong> {$firstName} {$lastName}</p>
                                    <p><strong>Email:</strong> {$email}</p>
                                    <p><strong>Phone:</strong> {$phone}</p>
                                    <p><strong>Message:</strong><br>{$messageContent}</p>
                                    <p><strong>Sender IP:</strong> {$ip}</p>
                                    <p><strong>Sender Location:</strong> {$location}</p>
                                    <p>Please review the submission and do the needful.</p>
                                    <p style='margin-top:25px;'>Best regards,<br><strong>Cypherox Technologies – Website Contact Alert</strong></p>
                                    <a href='https://www.cypherox.com/' style='display:inline-block; padding:12px 20px; background:#f26e65; color:#fff; text-decoration:none; border-radius:6px;'>Visit Our Website</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>";

        $mailSales->AltBody = "New Contact Form Submission – Cypherox Technologies\n\n
        Hello Team,\n
        A new contact form submission has been received through the website.\n
        Enclosed are the client details:\n
        Name: $firstName $lastName\n
        Email: $email\n
        Phone: $phone\n
        Message: $messageContent\n
        Sender IP: $ip\n
        Sender Location: $location\n
        \nPlease review the submission and do the needful.\n
        Best regards,\n
        Cypherox Technologies – Website Contact Alert";

        if($mailSales->send())
        {
             // --- Send automated Thank You email to requester ---
            $mailRequester = new PHPMailer(true);
            try {
                $mailRequester->isSMTP();
                $mailRequester->Host       = $host;
                $mailRequester->SMTPAuth   = true;
                $mailRequester->Username   = $username;
                $mailRequester->Password   = $psw;
                $mailRequester->SMTPSecure = $SMTPSecure;
                $mailRequester->Port       = $port;

                $mailRequester->setFrom($carrearautomatedmailreply, 'Cypherox Technologies Pvt. Ltd.');
                $mailRequester->addAddress($email, $firstName);
                $mailRequester->addReplyTo($carrearautomatedmailreply, 'Cypherox Technologies Pvt. Ltd.');


                $mailRequester->isHTML(true);
               $mailRequester->Subject = "Thank You for Contacting Cypherox Technologies Pvt. Ltd.";
        $svg = '   <tr>
            <td bgcolor="#f26e65" align="center" style="padding:25px 20px; color:#ffffff; font-size:22px; font-weight:bold;">
              <a href="https://www.cypherox.com/" target="_blank" style="display:inline-block; margin-bottom:15px;">
                <img src="https://cypherox.com/assets/img/email-logo.png" /> 
              </a>
              <div style="font-size:22px; line-height:28px; font-weight:bold;">Thank You for Contacting!</div>
            </td>
          </tr>';
        $mailRequester->Body = "
        <!DOCTYPE html>
        <html lang='en'>
        <head><meta charset='UTF-8'><title>Thank You</title></head>
        <body style='font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0;'>
            <table width='100%' cellpadding='0' cellspacing='0'>
                <tr>
                    <td align='center' style='padding:30px 15px;'>
                        <table width='600' style='background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,0.05);'>
                           {$svg}
                            <tr>
                                <td style='padding:30px 25px; color:#868686; font-size:16px; line-height:1.6;'>
                                    <p>Hello {$firstName} {$lastName},</p>
                                    <p>Thank you for reaching out to <strong>Cypherox Technologies Pvt. Ltd.</strong>. We’ve received your inquiry and will get back to you shortly.</p>
                                    <p>Best regards,<br><strong>Team Cypherox Technologies Pvt. Ltd.</strong></p>
                                    <a href='https://www.cypherox.com/' style='display:inline-block; padding:12px 20px; background:#f26e65; color:#fff; text-decoration:none; border-radius:6px;'>Visit Our Website</a>
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor='#f9f9f9' style='padding:20px; font-size:13px; color:#868686; text-align:center;'>
                                    This is an automated email, please do not reply.<br>
                                    &copy; 2025 <a href='https://www.cypherox.com/' style='color:#868686; text-decoration:none;'>Cypherox Technologies Pvt. Ltd.</a>. All Rights Reserved.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>";

                $mailRequester->send();

                echo "Thank you, $firstName! Your request has been submitted successfully.";
            } catch (Exception $e) {
                http_response_code(500);
                exit("Could not send Thank You email. Mailer Error: {$mailRequester->ErrorInfo}");
            }
        }
    } catch (Exception $e) {
        http_response_code(500);
        exit("Could not send email to Sales Team. Mailer Error: {$mailSales->ErrorInfo}");
    }

} else {
    http_response_code(405);
    exit("Invalid request method.");
}
?>
