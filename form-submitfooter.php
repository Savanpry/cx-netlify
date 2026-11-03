<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

include("maildetails.php"); // SMTP credentials: $host, $username, $psw, $SMTPSecure, $port, etc.

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Sanitize and collect input
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $recaptcha_response = $_POST['captchavalidation'];


    // Validate
    if (empty($name) || empty($email) || empty($recaptcha_response)) {
        http_response_code(400);
       exit("All required fields must be filled.");
    }

    if($captchaCheckd) {

        $serverCode = $_SESSION['captcha_code'];
        if ($recaptcha_response != $serverCode) {
            http_response_code(400);
            exit("Invalid captcha, try again!");
        }

        unset($_SESSION['captcha_code']);

        // $response = file_get_contents(
        //     "https://www.google.com/recaptcha/api/siteverify?secret="
        //     . $captchSecreateKey . "&response=" . $recaptcha_response
        // );
    
        // $responseKeys = json_decode($response, true);
    
        // if(intval($responseKeys["success"]) !== 1) {
        //     http_response_code(400);
        //     exit("Captcha failed. Please try again.");
        // }

        // if(!$recaptcha_response || empty($recaptcha_response)) {
        //     http_response_code(400);
        //     exit("Captcha failed. Please try again.");
        // }

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
        $apiUrl = $apiUrl."/api/talk-to-us-form";
        $apiData = [
            "name" => $name,
            "email" => $email,
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
    }

    // --- Send email to HR ---
    try {
        $mailHR = new PHPMailer(true);
        $mailHR->isSMTP();
        $mailHR->Host       = $host;
        $mailHR->SMTPAuth   = true;
        $mailHR->Username   = $username;
        $mailHR->Password   = $psw;
        $mailHR->SMTPSecure = $SMTPSecure;
        $mailHR->Port       = $port;

        $mailHR->addAddress($talktoreplyautomated);
        if($env != 'testing') {
            $mailHR->addCC($cc1);
        }
        $mailHR->addReplyTo($email, $name);

        $mailHR->CharSet = 'UTF-8';

        $mailHR->isHTML(true);
        $mailHR->Subject = "New General Inquiry Received – Cypherox Technologies Pvt. Ltd.";
        $svgHR = '   <tr>
            <td bgcolor="#f26e65" align="center" style="padding:25px 20px; color:#ffffff; font-size:22px; font-weight:bold;">
              <a href="https://www.cypherox.com/" target="_blank" style="display:inline-block; margin-bottom:15px;">
                <img src="https://cypherox.com/assets/img/email-logo.png" /> 
              </a>
              <div style="font-size:22px; line-height:28px; font-weight:bold;">New Contact Submission</div>
            </td>
        </tr>';


        $mailHR->Body = "
            <!DOCTYPE html>
            <html lang='en'>
            <head><meta charset='UTF-8'><title>New General Inquiry</title></head>
            <body style='font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0;'>
                <table width='100%' cellpadding='0' cellspacing='0'>
                    <tr>
                        <td align='center' style='padding:30px 15px;'>
                            <table width='600' style='background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,0.05);'>
                               {$svgHR}
                                <tr>
                                    <td style='padding:30px 25px; color:#444; font-size:16px; line-height:1.6;'>
                                        <h2 style='margin:0 0 15px; font-size:20px; color:#333;'>Hello Team,</h2>
                                        <p>A new inquiry has been submitted through the website.</p>
                                        <p>Enclosed are the details:</p>
                                        <p><strong>Name:</strong> {$name}</p>
                                        <p><strong>Email:</strong> {$email}</p>
                                        <p><strong>Sender IP:</strong> {$ip}</p>
                                        <p><strong>Sender Location:</strong> {$location}</p>
                                        <p>Please review the inquiry and do the needful.</p>
                                        <p style='margin-top:25px;'>Best regards,<br><strong>Cypherox Technologies Pvt. Ltd. – Website Inquiry Alert</strong></p>
                                         <a href='https://www.cypherox.com/' style='display:inline-block; padding:12px 20px; background:#f26e65; color:#fff; text-decoration:none; border-radius:6px;'>Visit Our Website</a>
                                    </td>
                                </tr>
                               
                            </table>
                        </td>
                    </tr>
                </table>
            </body>
            </html>";

        $mailHR->AltBody = "New Contact Form Submission\nName: $name\nEmail: $email\nSender IP: $ip\nSender Location: $location";


        $mailHR->send();

        // --- Send automated thank-you email to user ---
        $mailUser = new PHPMailer(true);
        $mailUser->isSMTP();
        $mailUser->Host       = $host;
        $mailUser->SMTPAuth   = true;
        $mailUser->Username   = $username;
        $mailUser->Password   = $psw;
        $mailUser->SMTPSecure = $SMTPSecure;
        $mailUser->Port       = $port;

        $mailUser->setFrom($talktoreplyautomated, 'Cypherox Technologies Pvt. Ltd.');
        $mailUser->addAddress($email, $name);
        $mailUser->addReplyTo($talktoreplyautomated, 'Cypherox Technologies Pvt. Ltd.');

        $mailUser->isHTML(true);
        $mailUser->Subject = "Thank You for Contacting Cypherox Technologies Pvt. Ltd.";
        $svg = '   <tr>
            <td bgcolor="#f26e65" align="center" style="padding:25px 20px; color:#ffffff; font-size:22px; font-weight:bold;">
              <a href="https://www.cypherox.com/" target="_blank" style="display:inline-block; margin-bottom:15px;">
                <img src="https://cypherox.com/assets/img/email-logo.png" /> 
              </a>
              <div style="font-size:22px; line-height:28px; font-weight:bold;">Thank You for Contacting!</div>
            </td>
          </tr>';
        $mailUser->Body = "
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
                                    <p>Hello {$name},</p>
                                    <p>Thank you for reaching out to <strong>Cypherox Technologies Pvt. Ltd.</strong>. We’ve received your inquiry and will get back to you shortly.</p>
                                    <p>Best regards,<br><strong>Team Cypherox Technologies Pvt. Ltd.</strong></p>
                                    <a href='https://www.cypherox.com/' style='display:inline-block; padding:12px 20px; background:#f26e65; color:#fff; text-decoration:none; border-radius:6px;'>Visit Our Website</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>";

        $mailUser->AltBody = "Hello $name,\n\nThank you for reaching out to Cypherox Technologies Pvt. Ltd.. We’ve received your inquiry and will get back to you shortly.\n\nBest regards,\nTeam Cypherox Technologies Pvt. Ltd.";

        $mailUser->send();

        echo "Thank you, $name! Your message has been sent and we have sent a confirmation email.";

    } catch (Exception $e) {
        http_response_code(500);
        exit("Mailer Error: {$mailHR->ErrorInfo}");
    }

} else {
    http_response_code(405); // Method Not Allowed
    exit("Invalid request method.");
}
