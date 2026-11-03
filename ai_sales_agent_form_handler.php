<?php

// Resolve shared includes whether handler sits in web root or a subfolder
$mailDetailsCandidates = [
    __DIR__ . '/maildetails.php',
    __DIR__ . '/../maildetails.php',
];
$mailDetailsLoaded = false;
foreach ($mailDetailsCandidates as $mailDetailsPath) {
    if (file_exists($mailDetailsPath)) {
        include($mailDetailsPath);
        $mailDetailsLoaded = true;
        break;
    }
}
 if (!$mailDetailsLoaded) {
    http_response_code(500);
    exit('Mail configuration file not found.');
}

$phpMailerBases = [
    __DIR__,
    dirname(__DIR__),
];
$phpMailerLoaded = false;
foreach ($phpMailerBases as $base) {
    $phpMailerFile = $base . '/PHPMailer/src/PHPMailer.php';
    if (file_exists($phpMailerFile)) {
        require $base . '/PHPMailer/src/PHPMailer.php';
        require $base . '/PHPMailer/src/SMTP.php';
        require $base . '/PHPMailer/src/Exception.php';
        $phpMailerLoaded = true;
        break;
    }
}
if (!$phpMailerLoaded) {
    http_response_code(500);
    exit('PHPMailer library not found.');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Invalid request method.");
}

// Collect and sanitize form data
$fullName =  htmlspecialchars(trim($_POST['name'] ?? $_POST['full_name'] ?? ''));
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$platform =   htmlspecialchars(trim($_POST['platform'] ?? $_POST['order_platform'] ?? ''));
$monthlyOrders = htmlspecialchars(trim($_POST['volume'] ?? $_POST['monthly_orders'] ?? ''));
$phone = htmlspecialchars(trim($_POST['phone'] ?? $_POST['tel'] ?? ''));
$business = htmlspecialchars(trim($_POST['business'] ?? ''));
if ($business === '' && $platform !== '') {
    $business = $platform;
}
$website = htmlspecialchars(trim($_POST['website'] ?? $_POST['store'] ?? ''));
$page_link = $_POST['page_link'] ?? '';
$recaptcha_response = $_POST['captchaInput'] ?? '';
$captchfieldname = $_POST['captchafield'] ?? '';

// Only validate captcha when the form still includes a captcha field
if (array_key_exists('captchaInput', $_POST)) {
    if ($captchfieldname != '') {
        $serverCode = $_SESSION[$captchfieldname] ?? '';
    } else {
        $serverCode = $_SESSION['captcha_code'] ?? '';
    }
    //echo $recaptcha_response ."!=". $serverCode;
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

/*if (empty($fullName) || empty($email) || empty($website)) {
    http_response_code(400);
    exit("All required fields must be filled.");
}*/

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit("Please enter a valid email address.");
}

// Normalize website for API/email (store URLs are often submitted without scheme)
if ($website !== '' && !preg_match('#^https?://#i', $website)) {
    $website = 'https://' . $website;
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
    if (($geoData['status'] ?? '') === 'success') {
        $location = $geoData['city'] . ', ' . $geoData['regionName'] . ', ' . $geoData['country'];
    }
}

// Generate UUID v4 for Lead API external id
$uuidData = random_bytes(16);
$uuidData[6] = chr(ord($uuidData[6]) & 0x0f | 0x40);
$uuidData[8] = chr(ord($uuidData[8]) & 0x3f | 0x80);
$leadId = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($uuidData), 4));
 if ($apiEnabled) {
    $leadApiUrl = rtrim($apiUrl, '/') . "/api/leads";

    
    $needsParts = [ "Business: {$business}","Website: {$website}"];
    if ($platform !== '') {
        $needsParts[] = "Order platform: {$platform}";
    }
    if ($monthlyOrders !== '') {
        $needsParts[] = "Monthly orders: {$monthlyOrders}";
    }
    if ($phone !== '') {
        $needsParts[] = "Phone: {$phone}";
    }

    // API requires phone|phone_number, platform|order_platform, and monthly_order
    $apiPlatform = $platform !== '' ? $platform : $business;
    $apiMonthlyOrder = $monthlyOrders !== '' ? $monthlyOrders : 'Not specified';
    $apiPhone = $phone !== '' ? $phone : 'Not provided';

    $postFields = [
        'id'                => $leadId,
        'lead_type'         => 'AI Sales Agent Inquiry',
        'status'            => 'Complete',
        'submitted_at'      => date('c'),
        'user_type'         => 'New User',
        'name'              => $fullName,
        'first_name'        => $firstName,
        'last_name'         => $lastName,
        'email'             => $email,
        'phone_number'      => $apiPhone,
        'business'          => $business,
        'website'           => $website,
        'platform'          => $apiPlatform,
        'monthly_order'     => $apiMonthlyOrder,
        'needs_description' => implode("\n", $needsParts),
        'ip_address'        => $ip,
        'current_location'  => $location,
        'page_link'         => $page_link,
    ];

    $ch = curl_init($leadApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'X-API-Secret: zeAJKWEuJahIHVTvBVwlym',
        'X-API-Token: 39ZYdEDYnuczCdtcNN93zycayr5pIErckwz0K4bEuw'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));

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

    $mailTeam->addAddress($hireingautomatemailreply);
    $mailTeam->addReplyTo($email, $fullName);
    if ($env != 'testing') {
        $mailTeam->addCC($cc1);
        if (!empty($hiringcc)) {
            $mailTeam->addCC($hiringcc);
        }
    }

    $svgHeader = '<tr>
        <td bgcolor="#f26e65" align="center" style="padding:25px 20px; color:#ffffff; font-size:22px; font-weight:bold;">
          <a href="https://www.cypherox.com/" target="_blank" style="display:inline-block; margin-bottom:15px;">
            <img src="https://cypherox.com/assets/img/email-logo.png" /> 
          </a>
          <div style="font-size:22px; line-height:28px; font-weight:bold;">New AI Sales Agent Inquiry</div>
        </td>
    </tr>';

    $mailTeam->CharSet = 'UTF-8';
    $mailTeam->isHTML(true);
    $mailTeam->Subject = "New AI Sales Agent Inquiry – Cypherox Technologies Pvt. Ltd.";

    $phoneDisplay = $phone !== '' ? $phone : '—';
    $platformDisplay = $platform !== '' ? $platform : '—';
    $monthlyOrdersDisplay = $monthlyOrders !== '' ? $monthlyOrders : '—';

    $mailTeam->Body = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
        <meta charset='UTF-8'>
        <title>New AI Sales Agent Inquiry</title>
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
                    <p>A new AI Sales Agent demo inquiry has been submitted through the website.</p>
                    <p><strong>Enclosed are the details:</strong></p>
                    <p><strong>Name:</strong> {$fullName}</p>
                    <p><strong>Email:</strong> {$email}</p>
                    <p><strong>Phone:</strong> {$phoneDisplay}</p>
                    <p><strong>Business:</strong> {$business}</p>
                    <p><strong>Website:</strong> {$website}</p>
                    <p><strong>Order platform:</strong> {$platformDisplay}</p>
                    <p><strong>Monthly orders:</strong> {$monthlyOrdersDisplay}</p>
                    <p><strong>Lead Type:</strong> AI Sales Agent Inquiry</p>
                    <p><strong>Sender IP:</strong> {$ip}</p>
                    <p><strong>Sender Location:</strong> {$location}</p>
                    <p>Please review the submission and do the needful.</p>
                    <p style='margin-top:20px; font-weight:bold;'>Best regards,<br>Cypherox Technologies – Website Sales Alert</p>
                    <a href='https://www.cypherox.com/' style='display:inline-block; padding:12px 20px; background:#f26e65; color:#fff; text-decoration:none; border-radius:6px;'>Visit Our Website</a>
                    </td>
                </tr>
                </table>
            </td>
            </tr>
        </table>
        </body>
        </html>";

    $mailTeam->AltBody = "New AI Sales Agent Inquiry – Cypherox Technologies\n\n
        Hello Team,\n
        A new AI Sales Agent demo inquiry has been submitted through the website.\n
        Name: $fullName\n
        Email: $email\n
        Phone: $phoneDisplay\n
        Business: $business\n
        Website: $website\n
        Order platform: $platformDisplay\n
        Monthly orders: $monthlyOrdersDisplay\n
        Lead Type: AI Sales Agent Inquiry\n
        Sender IP: $ip\n
        Sender Location: $location\n
        \nPlease review the submission and do the needful.\n
        Cypherox Technologies – Website Sales Alert";

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

        $mailCandidate->setFrom($hireingautomatemailreply, 'Cypherox Technologies Pvt. Ltd.');
        $mailCandidate->addAddress($email, $fullName);
        $mailCandidate->addReplyTo($hireingautomatemailreply, 'Cypherox Technologies Pvt. Ltd.');

        $mailCandidate->isHTML(true);
        $mailCandidate->Subject = "Thank You for Your AI Sales Agent Demo Request";

        $svg = '<tr>
                    <td bgcolor="#f26e65" align="center" style="padding:25px 20px; color:#ffffff; font-size:22px; font-weight:bold;">
                    <a href="https://www.cypherox.com/" target="_blank" style="display:inline-block; margin-bottom:15px;">
                    <img src="https://cypherox.com/assets/img/email-logo.png" /> 
                    </a>
                    <div style="font-size:22px; line-height:28px; font-weight:bold;">Thank You for Reaching Out!</div>
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
                                        <p>Thank you for requesting a demo of our AI Sales Agent. We appreciate your interest in <strong>Cypherox Technologies Pvt. Ltd.</strong></p>
                                        <p>Our team will review your details and connect with you shortly to schedule a personalised walkthrough.</p>
                                        <p>Warm regards,<br><strong>Cypherox Technologies Pvt. Ltd.</strong></p>
                                        <a href='https://www.cypherox.com/ai-sales-agent-for-ecommerce' style='display:inline-block; padding:12px 20px; background:#f26e65; color:#fff; text-decoration:none; border-radius:6px;'>Visit Our Website</a>
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

        $mailCandidate->AltBody = "Hello $firstName, Thank you for requesting an AI Sales Agent demo. We will connect with you shortly.";

        $mailCandidate->send();

        echo "Thank you, $firstName! Your AI Sales Agent inquiry has been submitted successfully.";
    }

} catch (Exception $e) {
    http_response_code(500);
    exit("Mailer Error: {$mailTeam->ErrorInfo}");
}
?>
