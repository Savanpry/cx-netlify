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
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['tel'] ?? '');
    $apply_for_role = (is_array($_POST['applyforjob'])) ? implode(", ",$_POST['applyforjob']) : htmlspecialchars($_POST['applyforjob'] ?? '');
    $messageContent = htmlspecialchars($_POST['cover-letter'] ?? '');
    $recaptcha_response = $_POST['captchaInput'];
    $captchfieldname=$_POST['captchafield'] ?? '';
    $name = $firstName.' '.$lastName;

    // Ticket data
    $data = [
        "ticket" => [
            "subject" => "New Lead from Website - $name",
            "comment" => [
                "body" => "Lead Details:\nName: $name\nEmail: $email\nPhone: $phone\nApply For Roles: $apply_for_role\nMessage: $messageContent"
            ],
            "requester" => [
                "name" => $name,
                "email" => $email
            ],
            "priority" => "normal"
        ]
    ];

    if($captchaCheckd) {
        if($captchfieldname!=''){
            $serverCode=$_SESSION[$captchfieldname];
        }else{
            $serverCode = $_SESSION['captcha_code'];
        }
        if ($recaptcha_response != $serverCode) {
            http_response_code(400);
            exit("Invalid captcha, try again! validate:".$serverCode);
        }
        if($captchfieldname!=''){
            unset($_SESSION[$captchfieldname]);
        }else{
            unset($_SESSION['captcha_code']);
        }
    }


    // Zend API request
    // $ch = curl_init("https://$subdomain.zendesk.com/api/v2/tickets.json");
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    // curl_setopt($ch, CURLOPT_USERPWD, "$emailZendesk/token:$api_token");
    // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // $response = curl_exec($ch);
    // $err = curl_error($ch);
    // curl_close($ch);

    // if ($err) {
    //     echo "cURL Error #:" . $err;
    //     exit();
    // }

    // ✅ Check Zendesk response
    //$decoded = json_decode($response, true);

    // if (isset($decoded['ticket']['id'])) {
    //     echo "✅ Lead submitted successfully! Ticket ID: " . $decoded['ticket']['id'];
    // } else {
    //     echo "❌ Failed to create lead. Response: " . $response;
    // }
 
    if (empty($firstName) || empty($lastName) || empty($email) || empty($messageContent) || empty($recaptcha_response)) {
    //if (empty($firstName) || empty($lastName) || empty($email) || empty($messageContent)) {
        http_response_code(400);
        exit("All required fields must be filled.");
    } 
    // $response = file_get_contents(
    //     "https://www.google.com/recaptcha/api/siteverify?secret="
    //     . $captchSecreateKey . "&response=" . $recaptcha_response
    // );
 
    // $responseKeys = json_decode($response, true);
 
    // if(intval($responseKeys["success"]) !== 1) {
    //     exit("Captcha failed. Please try again.");
    // }

    
    
    // --- Get Client IP Address ---
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    }

    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $ip = 'Unknown';
    }
    // if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    //     $ip = $_SERVER['HTTP_CLIENT_IP'];
    // } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    // } else {
    //     $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    // }

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
            $apiUrl = $apiUrl."/api/sales-inquiry";
            $apiData = [
                "first_name" => $firstName,
                "last_name" => $lastName,
                "email" => $email,
                "phone_number" => $phone,
                "apply_for_roles" => $_POST['applyforjob'],
                "needs_description" => $messageContent,
                "ip_address" => $ip,
                "current_location" => $location,
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
    $mailSales->CharSet = 'UTF-8';
    try {
        $mailSales->isSMTP();
        $mailSales->Host       = $host;
        $mailSales->SMTPAuth   = true;
        $mailSales->Username   = $username;
        $mailSales->Password   = $psw;
        $mailSales->SMTPSecure = $SMTPSecure;
        $mailSales->Port       = $port;

        $mailSales->addAddress($hireingautomatemailreply); // Sales team
        if($env != 'testing') {
            $mailSales->addCC($cc1);
            $mailSales->addCC($hiringcc);
        }

        $mailSales->isHTML(true);
        $svgHR = '<tr>
                    <td bgcolor="#f26e65" align="center" style="padding:25px 20px; color:#ffffff; font-size:22px; font-weight:bold;">
                        <a href="https://www.cypherox.com/" target="_blank" style="display:inline-block; margin-bottom:15px;">
                            <img src="https://cypherox.com/assets/img/email-logo.png" alt="Cypherox Logo" />
                        </a>
                        <p style="font-size:22px; line-height:28px; font-weight:bold; margin:0;">New Hiring Inquiry Received</p>
                    </td>
                </tr>';

                $mailSales->Subject = "New Hiring Inquiry Received – Cypherox Technologies";

                $mailSales->Body = "
                                <!DOCTYPE html>
                                <html lang='en'>
                                <head>
                                    <meta charset='UTF-8'>
                                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                    <title>New Hiring Inquiry Received</title>
                                </head>
                                <body style='font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0;'>
                                    <table width='100%' cellpadding='0' cellspacing='0'>
                                        <tr>
                                            <td align='center' style='padding:30px 15px;'>
                                                <table width='600' style='background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,0.05);'>
                                                    {$svgHR}
                                                    <tr>
                                                        <td style='padding:30px 25px; color:#555555; font-size:16px; line-height:1.6;'>
                                                            <p>Hello Team,</p>

                                                            <p>A new inquiry has been submitted showing interest in hiring us for our services.</p>

                                                            <p>Enclosed are the client details:</p>
                                                            <p>
                                                                <strong>Name:</strong> {$firstName} {$lastName} <br>
                                                                <strong>Email:</strong> {$email}<br>
                                                                <strong>Phone:</strong> {$phone}<br>
                                                                <strong>Service Requested:</strong> {$apply_for_role}<br>
                                                                <strong>Message:</strong> {$messageContent}<br>
                                                                <strong>Sender IP:</strong> {$ip}<br>
                                                                <strong>Sender Location:</strong> {$location}
                                                            </p>
                                                            <p>Please take the necessary action and revert with the required details.</p>
                                                            <p style='margin-top:25px;'>Best regards,<br><strong>Cypherox Technologies Pvt. Ltd. – Website Lead Alert</strong></p>
                                                              <a href='https://www.cypherox.com/' style='display:inline-block; padding:12px 20px; background:#f26e65; color:#fff; text-decoration:none; border-radius:6px;'>Visit Our Website</a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </body>
                                </html>";

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

                $mailRequester->setFrom($hireingautomatemailreply, 'Cypherox Technologies Pvt. Ltd.');
                $mailRequester->addAddress($email, $firstName);
                $mailRequester->addReplyTo($hireingautomatemailreply, 'Cypherox Technologies Pvt. Ltd.');


                $mailRequester->isHTML(true);
                $mailRequester->Subject = "Thank You for Your Interest in Cypherox Technologies Pvt. Ltd.";

                 $svg = '   <tr>
            <td bgcolor="#f26e65" align="center" style="padding:25px 20px; color:#ffffff; font-size:22px; font-weight:bold;">
              <a href="https://www.cypherox.com/" target="_blank" style="display:inline-block; margin-bottom:15px;">
                <img src="https://cypherox.com/assets/img/email-logo.png" /> 
              </a>
              <div style="font-size:22px; line-height:28px; font-weight:bold;">Thank You for Your Interest!</div>
            </td>
          </tr>';

                // HTML Email Template
                $mailRequester->Body = "
                <!DOCTYPE html>
                <html lang='en'>
                <head><meta charset='UTF-8'><title>Thank You</title></head>
                <body style='font-family: Arial, Helvetica, sans-serif; background:#f4f4f4; margin:0; padding:0;'>
                    <table width='100%' cellpadding='0' cellspacing='0'>
                        <tr>
                            <td align='center' style='padding:30px 15px;'>
                                <table width='600' style='background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,0.05);'>
                                   {$svg}
                                    <tr>
                                        <td style='padding:30px 25px; color:#868686; font-size:16px; line-height:1.6;'>
                                            <h2 style='color:#f26e65;'>Hello {$firstName},</h2>
                                            <p>Thank you for contacting <strong>Cypherox Technologies Pvt. Ltd.</strong> regarding your business needs.</p>
                                            <p>We’ve received your request, and one of our representatives will connect with you soon to understand your requirements and share the right solutions.</p>
                                            <p>We appreciate your interest in partnering with us and look forward to supporting your goals.</p>
                                            <p>Warm regards,<br><strong>Cypherox Technologies Pvt. Ltd.</strong></p>
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

                $mailRequester->AltBody = "Hello $firstName, Thank you for contacting Cypherox Technologies Pvt. Ltd.. We will connect with you soon.";

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
