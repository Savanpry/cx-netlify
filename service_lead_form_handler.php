<?php

include("maildetails.php");
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Invalid request method.");
}

$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$company = htmlspecialchars(trim($_POST['company'] ?? ''));
$build = htmlspecialchars(trim($_POST['build'] ?? ''));
$stack = htmlspecialchars(trim($_POST['stack'] ?? ''));
$timeline = htmlspecialchars(trim($_POST['timeline'] ?? ''));
$phone = htmlspecialchars(trim($_POST['tel'] ?? $_POST['phone'] ?? ''));
$coverLetter = htmlspecialchars(trim($_POST['cover-letter'] ?? $_POST['cover_letter'] ?? ''));
$serviceRole = htmlspecialchars(trim($_POST['service_role'] ?? 'Hiring Inquiry'));
$page_link = $_POST['page_link'] ?? '';

$firstName = htmlspecialchars(trim($_POST['first_name'] ?? ''));
$lastName = htmlspecialchars(trim($_POST['last_name'] ?? ''));
$fullName = htmlspecialchars(trim($_POST['name'] ?? ''));

// Contact-style form (first/last name) or scoped form (single name)
if ($firstName === '' && $fullName !== '') {
    $nameParts = preg_split('/\s+/', trim($fullName));
    $firstName = $nameParts[0] ?? '';
    $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '';
} elseif ($fullName === '' && ($firstName !== '' || $lastName !== '')) {
    $fullName = trim($firstName . ' ' . $lastName);
}

$isScopedForm = ($company !== '' || $build !== '' || $stack !== '' || $timeline !== '');
$isContactForm = ($coverLetter !== '' || isset($_POST['first_name']) || isset($_POST['cover-letter']));

$applyForRoles = $_POST['applyforjob'] ?? [];
if (!is_array($applyForRoles)) {
    $applyForRoles = [$applyForRoles];
}
$applyForRoles = array_values(array_filter(array_map('strval', $applyForRoles)));
if (empty($applyForRoles)) {
    $applyForRoles = ['fullStack'];
}

if (empty($email) || empty($fullName)) {
    http_response_code(400);
    exit("All required fields must be filled.");
}

if ($isScopedForm) {
    if (empty($company) || empty($build) || empty($stack) || empty($timeline)) {
        http_response_code(400);
        exit("All required fields must be filled.");
    }
} elseif ($isContactForm) {
    if (empty($firstName) || empty($lastName) || empty($coverLetter)) {
        http_response_code(400);
        exit("All required fields must be filled.");
    }
} else {
    http_response_code(400);
    exit("All required fields must be filled.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit("Please enter a valid email address.");
}

if ($isScopedForm) {
    $needsParts = [
        "Company: {$company}",
        "What you need to build: {$build}",
        "Current stack: {$stack}",
        "Timeline: {$timeline}",
        "Service: {$serviceRole}",
    ];
    $messageContent = implode("\n", $needsParts);
} else {
    $messageContent = $coverLetter;
    if ($serviceRole !== '') {
        $messageContent .= "\nService: {$serviceRole}";
    }
}
$apply_for_role = implode(', ', $applyForRoles);
$phoneDisplay = $phone !== '' ? $phone : 'Not provided';
$companyDisplay = $company !== '' ? $company : 'N/A';
$needsHtml = nl2br($messageContent);

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
    if (($geoData['status'] ?? '') === 'success') {
        $location = $geoData['city'] . ', ' . $geoData['regionName'] . ', ' . $geoData['country'];
    }
}

if ($apiEnabled) {
    $salesApiUrl = rtrim($apiUrl, '/') . "/api/sales-inquiry";
    $apiData = [
        "first_name" => $firstName,
        "last_name" => $lastName,
        "email" => $email,
        "phone_number" => $phoneDisplay,
        "apply_for_roles" => $applyForRoles,
        "needs_description" => $messageContent,
        "company" => $company,
        "ip_address" => $ip,
        "current_location" => $location,
        "page_link" => $page_link,
    ];

    $ch = curl_init($salesApiUrl);
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

    if ($error) {
        http_response_code(500);
        exit("Failed to connect to API. Error: " . $error);
    } elseif ($httpCode < 200 || $httpCode >= 300) {
        http_response_code($httpCode);
        exit("API Error ($httpCode): " . $response);
    }
}

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

    $mailSales->addAddress($hireingautomatemailreply);
    if ($env != 'testing') {
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
                                    <p>A new inquiry has been submitted from the service lead form.</p>
                                    <p>
                                        <strong>Name:</strong> {$fullName}<br>
                                        <strong>Email:</strong> {$email}<br>
                                        <strong>Phone:</strong> {$phoneDisplay}<br>
                                        <strong>Company:</strong> {$companyDisplay}<br>
                                        <strong>Service Requested:</strong> {$serviceRole} ({$apply_for_role})<br>
                                        <strong>Needs:</strong><br>{$needsHtml}<br>
                                        <strong>Page:</strong> {$page_link}<br>
                                        <strong>Sender IP:</strong> {$ip}<br>
                                        <strong>Sender Location:</strong> {$location}
                                    </p>
                                    <p style='margin-top:25px;'>Best regards,<br><strong>Cypherox Technologies Pvt. Ltd. – Website Lead Alert</strong></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
        </html>";

    if ($mailSales->send()) {
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
            $mailRequester->Body = "
                <!DOCTYPE html>
                <html lang='en'>
                <head><meta charset='UTF-8'><title>Thank You</title></head>
                <body style='font-family: Arial, Helvetica, sans-serif; background:#f4f4f4; margin:0; padding:0;'>
                    <table width='100%' cellpadding='0' cellspacing='0'>
                        <tr>
                            <td align='center' style='padding:30px 15px;'>
                                <table width='600' style='background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,0.05);'>
                                    <tr>
                                        <td bgcolor='#f26e65' align='center' style='padding:25px 20px; color:#ffffff; font-size:22px; font-weight:bold;'>
                                            <a href='https://www.cypherox.com/' target='_blank' style='display:inline-block; margin-bottom:15px;'>
                                                <img src='https://cypherox.com/assets/img/email-logo.png' />
                                            </a>
                                            <div style='font-size:22px; line-height:28px; font-weight:bold;'>Thank You for Your Interest!</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding:30px 25px; color:#868686; font-size:16px; line-height:1.6;'>
                                            <h2 style='color:#f26e65;'>Hello {$firstName},</h2>
                                            <p>Thank you for contacting <strong>Cypherox Technologies Pvt. Ltd.</strong> regarding your hiring needs.</p>
                                            <p>We've received your request, and one of our representatives will connect with you soon.</p>
                                            <p>Warm regards,<br><strong>Cypherox Technologies Pvt. Ltd.</strong></p>
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
?>
