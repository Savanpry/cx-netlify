<?php
session_start();

if($_GET['captcha_generate']) {
    //$captcha = trim(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789'), 0, 7));

    $captcha = trim(substr(str_shuffle('0123456789'), 0, 5));

    if($_GET['functionName'] == 'refreshCaptcha1') {
        $_SESSION['captcha_code'] = $captcha;
    }
    else if($_GET['functionName'] == 'refreshCaptcha2') {
        $_SESSION['captcha_code2'] = $captcha;
    }
    else if($_GET['functionName'] == 'refreshCaptcha3') {
        $_SESSION['captcha_code3'] = $captcha;
    } else {
        $_SESSION['captcha_code'] = $captcha;
    }
    echo json_encode(['captcha' => $captcha]);
}

$env = "production";

// Get the current host (domain)
$currentHost = $_SERVER['HTTP_HOST'] ?? '';

// Normalize with scheme
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$currentUrl = $scheme . "://" . $currentHost;

// Check environment
if ($currentUrl === "https://www.cypherox.com") {
    $env = "production";
} else {
    $env = "testing";
}
//$env = "testing";

$host       = ($env == "testing") ? 'smtp.gmail.com' : 'mail.cypherox.com';
$username   = ($env == "testing") ? 'testerdemo.twenty20@gmail.com' : 'info@cypherox.com';
$psw  = ($env == "testing") ? 'hnkuqarrephuclvy' : 'E/|3D*zubOjF1|CT';
$port       = 465;
$SMTPSecure = 'ssl';
$SMTPAuth = true;
$carrearautomatedmailreply = ($env == "testing") ? 'crazycoder09@gmail.com' : "hr@cypherox.com";
$hireingautomatemailreply = ($env == "testing") ? 'crazycoder09@gmail.com' : "sales@cypherox.com";
$talktoreplyautomated = ($env == "testing") ? 'crazycoder09@gmail.com' : "info@cypherox.com";
$cc1 = "vipinraj@cypherox.com";
$carrercc = "tulika.hrcx@cypherox.com";
$hiringcc = "info@cypherox.com";
$talktocc = "info@cypherox.com";
$captchSecreateKey = "6LfactIrAAAAAFguv9IDbDtkXQASK_y30_erRMs5";

// Zendesk credentials
$subdomain = "cypheroxtechnologiespvt";  // e.g. yourcompany.zendesk.com
$emailZendesk = "sales@cypherox.com"; // Zendesk registered email
$api_token = "Ak8nge0e8CbwVSjrti9olIVeiRQvHxATTCXbDhJU";

//Captcha Checked

$captchaCheckd = ($env != "testing");

//For Our panel TOken
$apiEnabled = true;
$apiUrl = "https://cxportal.cypherox.com";
$apiToken = 'sjdfhsdfsdjfhshdfkjsd';
?>