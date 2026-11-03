<?php

include("maildetails.php"); // SMTP credentials

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Collect and sanitize form data
    $fullName = htmlspecialchars($_POST['full_name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $contributionType = htmlspecialchars($_POST['contribution_type'] ?? '');
    $focusArea = htmlspecialchars($_POST['focus_area'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');
    $page_link = $_POST['page_link'] ?? '';
    $recaptcha_response = $_POST['captchaInput'] ?? '';
    $captchfieldname = $_POST['captchafield'] ?? '';

    if ($captchaCheckd) {
        if ($captchfieldname != '') {
            $serverCode = $_SESSION[$captchfieldname];
        } else {
            $serverCode = $_SESSION['captcha_code'];
        }
        if ($recaptcha_response != $serverCode) {
            http_response_code(400);
            exit("Invalid captcha, try again!");
        }

        if ($captchfieldname != '') {
            unset($_SESSION[$captchfieldname]);
        } else {
            unset($_SESSION['captcha_code']);
        }
    }

    if (empty($fullName) || empty($email) || empty($contributionType) || empty($focusArea)) {
        http_response_code(400);
        exit("All required fields must be filled.");
    }

    // Split full name into first / last for API compatibility
    $nameParts = preg_split('/\s+/', trim($fullName));
    $firstName = $nameParts[0] ?? '';
    $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '';

    // --- Get Client IP and approximate location ---
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    }

    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $ip = 'Unknown';
    }

    $location = 'Unknown';
    $geo = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,regionName,city");
    if ($geo) {
        $geoData = json_decode($geo, true);
        if ($geoData['status'] === 'success') {
            $location = $geoData['city'] . ', ' . $geoData['regionName'] . ', ' . $geoData['country'];
        }
    }

    if ($apiEnabled) {
        // --- API endpoint and token ---
        $apiUrl = $apiUrl . "/api/csr-form";

        // --- Collect form fields ---
        $postFields = [
            'full_name'          => $fullName,
            'first_name'         => $firstName,
            'last_name'          => $lastName,
            'email'              => $email,
            'phone_number'       => $phone,
            'contribution_type'  => $contributionType,
            'focus_area'         => $focusArea,
            'message'            => $message,
            'ip_address'         => $ip,
            'current_location'   => $location,
            'page_link'          => $page_link
        ];

        // --- Initialize cURL ---
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-API-Secret: zeAJKWEuJahIHVTvBVwlym',
            'X-API-Token: 39ZYdEDYnuczCdtcNN93zycayr5pIErckwz0K4bEuw'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));

        // --- Execute request ---
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

    // --- Send email to team ---
    $mailTeam = new PHPMailer(true);
    try {
        $mailTeam->isSMTP();
        $mailTeam->Host       = $host;
        $mailTeam->SMTPAuth   = true;
        $mailTeam->Username   = $username;
        $mailTeam->Password   = $psw;
        $mailTeam->SMTPSecure = $SMTPSecure;
        $mailTeam->Port       = $port;

        $mailTeam->addAddress($talktoreplyautomated);
        $mailTeam->addReplyTo($email, $fullName);
        if ($env != 'testing') {
            $mailTeam->addCC($cc1);
            if (!empty($talktocc)) {
                $mailTeam->addCC($talktocc);
            }
        }

        $svgHeader = '<tr>
            <td bgcolor="#f26e65" align="center" style="padding:25px 20px; color:#ffffff; font-size:22px; font-weight:bold;">
              <a href="https://www.cypherox.com/" target="_blank" style="display:inline-block; margin-bottom:15px;">
                <img src="https://cypherox.com/assets/img/email-logo.png" /> 
              </a>
              <div style="font-size:22px; line-height:28px; font-weight:bold;">New CSR Submission</div>
            </td>
        </tr>';

        $mailTeam->CharSet = 'UTF-8';
        $mailTeam->isHTML(true);
        $mailTeam->Subject = "New CSR Inquiry Received – Cypherox Technologies Pvt. Ltd.";

        $mailTeam->Body = "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
            <meta charset='UTF-8'>
            <title>New CSR Submission</title>
            </head>
            <body style='font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0;'>
            <table width='100%' cellpadding='0' cellspacing='0'>
                <tr>
                <td align='center' style='padding:30px 15px;'>
                    <table width='600' style='background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,0.05);'>
                    {$svgHeader}
                    <tr>
                        <td style='padding:25px 30px; color:#333; font-size:16px; line-height:1.6;'>
                        <h2 style='margin-top:0; color:#2a2a2a;'>Hello Team,</h2>
                        <p>A new CSR support inquiry has been submitted through the website.</p>
                        <p><strong>Enclosed are the details:</strong></p>
                        <p><strong>Name:</strong> {$fullName}</p>
                        <p><strong>Email:</strong> {$email}</p>
                        <p><strong>Phone:</strong> {$phone}</p>
                        <p><strong>Contribution Type:</strong> {$contributionType}</p>
                        <p><strong>Focus Area:</strong> {$focusArea}</p>
                        <p><strong>Message:</strong> {$message}</p>
                        <p><strong>Sender IP:</strong> {$ip}</p>
                        <p><strong>Sender Location:</strong> {$location}</p>
                        <p>Please review the submission and do the needful.</p>
                        <p style='margin-top:20px; font-weight:bold;'>Best regards,<br>Cypherox Technologies – Website CSR Alert</p>
                        <a href='https://www.cypherox.com/' style='display:inline-block; padding:12px 20px; background:#f26e65; color:#fff; text-decoration:none; border-radius:6px;'>Visit Our Website</a>
                        </td>
                    </tr>
                    </table>
                </td>
                </tr>
            </table>
            </body>
            </html>";

        $mailTeam->AltBody = "New CSR Inquiry Received – Cypherox Technologies\n\n
            Hello Team,\n
            A new CSR support inquiry has been submitted through the website.\n
            Name: $fullName\n
            Email: $email\n
            Phone: $phone\n
            Contribution Type: $contributionType\n
            Focus Area: $focusArea\n
            Message: $message\n
            Sender IP: $ip\n
            Sender Location: $location\n
            \nPlease review the submission and do the needful.\n
            Cypherox Technologies – Website CSR Alert";

        if ($mailTeam->send()) {
            // --- Send automated Thank You email ---
            $mailCandidate = new PHPMailer(true);
            $mailCandidate->isSMTP();
            $mailCandidate->Host       = $host;
            $mailCandidate->SMTPAuth   = true;
            $mailCandidate->Username   = $username;
            $mailCandidate->Password   = $psw;
            $mailCandidate->SMTPSecure = $SMTPSecure;
            $mailCandidate->Port       = $port;

            $mailCandidate->setFrom($talktoreplyautomated, 'Cypherox Technologies Pvt. Ltd.');
            $mailCandidate->addAddress($email, $fullName);
            $mailCandidate->addReplyTo($talktoreplyautomated, 'Cypherox Technologies Pvt. Ltd.');

            $mailCandidate->isHTML(true);
            $mailCandidate->Subject = "Thank You for Supporting Our CSR Work";

            $svg = '<tr>
                        <td bgcolor="#f26e65" align="center" style="padding:25px 20px; color:#ffffff; font-size:22px; font-weight:bold;">
                        <a href="https://www.cypherox.com/" target="_blank" style="display:inline-block; margin-bottom:15px;">
                        <img src="https://cypherox.com/assets/img/email-logo.png" /> 
                        </a>
                        <div style="font-size:22px; line-height:28px; font-weight:bold;">Thank You for Your Support!</div>
                        </td>
                    </tr>';

            $mailCandidate->Body = "
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
                                            <h2 style='color:#f26e65;'>Hello {$firstName},</h2>
                                            <p>Thank you for reaching out through our Corporate Social Responsibility page. We sincerely appreciate your interest in supporting <strong>Cypherox Technologies Pvt. Ltd.</strong> and our community initiatives.</p>
                                            <p>Our team will review your submission and connect with you if needed.</p>
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

            $mailCandidate->AltBody = "Hello $firstName, Thank you for supporting our CSR work. We will connect with you if needed.";

            $mailCandidate->send();

            echo "Thank you, $firstName! Your CSR inquiry has been submitted successfully.";
        }

    } catch (Exception $e) {
        http_response_code(500);
        exit("Mailer Error: {$mailTeam->ErrorInfo}");
    }

} else {
    http_response_code(405);
    exit("Invalid request method.");
}
?>
