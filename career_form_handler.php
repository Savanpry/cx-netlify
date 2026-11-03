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
    $firstName = htmlspecialchars($_POST['first_name'] ?? '');
    $lastName = htmlspecialchars($_POST['last_name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $career_location_post = htmlspecialchars($_POST['career_location_post'] ?? '');
    $role = htmlspecialchars($_POST['apply_for_role'] ?? '');
    $message = htmlspecialchars($_POST['cover-letter'] ?? '');
    $page_link = $_POST['page_link'] ?? '';
    $recaptcha_response = $_POST['captchaInput'];
    $captchfieldname=$_POST['captchafield'] ?? '';

    // if(empty($recaptcha_response)) {
    //     http_response_code(400);
    //     exit("Captcha failed. Please try again.");
    // }

    // $response = file_get_contents(
    //     "https://www.google.com/recaptcha/api/siteverify?secret="
    //     . $captchSecreateKey . "&response=" . $recaptcha_response
    // );

    // $responseKeys = json_decode($response, true);

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
    }

    

    
    if (empty($firstName) || empty($lastName) || empty($email) || empty($role) || empty($message) || !isset($_FILES['myfile'])) {
        http_response_code(400);
        exit("All required fields must be filled, including uploading your CV.");
    }

    

    // File upload validation
    $cv = $_FILES['myfile'];
    $maxSize = 10 * 1024 * 1024; // 10 MB
    $allowedTypes = ['application/pdf'];

    if ($cv['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        exit("Error uploading the CV file.");
    }

    if ($cv['size'] > $maxSize) {
        http_response_code(400);
        exit("CV file exceeds maximum size of 10 MB.");
    }

    if (!in_array(mime_content_type($cv['tmp_name']), $allowedTypes)) {
        http_response_code(400);
        exit("Only PDF files are allowed.");
    }

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

    if($apiEnabled) {
        // --- API endpoint and token ---
        $apiUrl = $apiUrl."/api/career-form";

        // --- Collect form fields ---
        $postFields = [
            'first_name'      => $firstName,
            'last_name'       => $lastName,
            'email'           => $email,
            'apply_for_role'  => $role,
            'phone_number'    => $phone,
            'about_yourself'  => $message,
            "ip_address"      => $ip,
            "location_id"     => ($career_location_post == 'Rajkot') ? 2 : 1,
            "current_location" => $location,
            'page_link'       => $page_link
        ];

        if (!empty($_FILES['myfile']['tmp_name'])) {
            $postFields['cv_file'] = new CURLFile($_FILES['myfile']['tmp_name'], $_FILES['myfile']['type'], $_FILES['myfile']['name']);
        }

        
        // --- Initialize cURL ---
        //
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'X-API-Secret: zeAJKWEuJahIHVTvBVwlym',
            'X-API-Token: 39ZYdEDYnuczCdtcNN93zycayr5pIErckwz0K4bEuw'
        ]);
        // Important: don't set Content-Type manually, let cURL handle it
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

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
        } else {
            echo "API Response: " . $response;
        }
    }


    // --- Send email to HR ---
    $mailHR = new PHPMailer(true);
    try {
        $mailHR->isSMTP();
        $mailHR->Host       = $host;
        $mailHR->SMTPAuth   = true;
        $mailHR->Username   = $username;
        $mailHR->Password   = $psw;
        $mailHR->SMTPSecure = $SMTPSecure;
        $mailHR->Port       = $port;

        $mailHR->addAddress($carrearautomatedmailreply); // HR Email
        $mailHR->addReplyTo($email, "$firstName $lastName");
         if($env != 'testing') {
            $mailHR->addCC($cc1);
            $mailHR->addCC($carrercc);
        }

        // Attach CV
        $mailHR->addAttachment($cv['tmp_name'], $cv['name']);
          $svgHR = '   <tr>
            <td bgcolor="#f26e65" align="center" style="padding:25px 20px; color:#ffffff; font-size:22px; font-weight:bold;">
              <a href="https://www.cypherox.com/" target="_blank" style="display:inline-block; margin-bottom:15px;">
                <img src="https://cypherox.com/assets/img/email-logo.png" /> 
              </a>
              <div style="font-size:22px; line-height:28px; font-weight:bold;">New Contact Submission</div>
            </td>
        </tr>';

        $mailHR->CharSet = 'UTF-8';
        $mailHR->isHTML(true);
        $mailHR->Subject = "New HR / Career Inquiry Received – Cypherox Technologies Pvt. Ltd.";
      
$mailHR->Body = "
<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <title>New Career Application</title>
</head>
<body style='font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0;'>
  <table width='100%' cellpadding='0' cellspacing='0'>
    <tr>
      <td align='center' style='padding:30px 15px;'>
        <table width='600' style='background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,0.05);'>
          {$svgHR}
          <tr>
            <td style='padding:25px 30px; color:#333; font-size:16px; line-height:1.6;'>
              <h2 style='margin-top:0; color:#2a2a2a;'>Hello HR Team,</h2>
              <p>A new application has been submitted through the website.</p>
              <p><strong>Enclosed are the candidate details:</strong></p>
              <p><strong>Name:</strong> {$firstName} {$lastName}</p>
              <p><strong>Email:</strong> {$email}</p>
              <p><strong>Phone:</strong> {$phone}</p>
              <p><strong>For Location:</strong> {$career_location_post}</p>
              <p><strong>Position Applied / Subject:</strong> {$role}</p>
              <p><strong>Message / Cover Note:</strong> {$message}</p>
              <p><strong>Sender IP:</strong> {$ip}</p>
              <p><strong>Sender Location:</strong> {$location}</p>
              <p>Please review the application and do the needful.</p>
              <p style='margin-top:20px; font-weight:bold;'>Best regards,<br>Cypherox Technologies – Website HR Alert</p>
               <a href='https://www.cypherox.com/' style='display:inline-block; padding:12px 20px; background:#f26e65; color:#fff; text-decoration:none; border-radius:6px;'>Visit Our Website</a>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>";

$mailHR->AltBody = "New HR / Career Inquiry Received – Cypherox Technologies\n\n
Hello HR Team,\n
A new application has been submitted through the website.\n
Enclosed are the candidate details:\n
Name: $firstName $lastName\n
Email: $email\n
Phone: $phone\n
For Location: $career_location_post\n
Position Applied / Subject: $role\n
Message / Cover Note: $coverLetter\n
Sender IP: $ip\n
Sender Location: $location\n
\nPlease review the application and do the needful.\n
Cypherox Technologies – Website HR Alert";
        if($mailHR->send()) {

            // --- Send automated Thank You email to Candidate ---
            $mailCandidate = new PHPMailer(true);
            $mailCandidate->isSMTP();
            $mailCandidate->Host       = $host;
            $mailCandidate->SMTPAuth   = true;
            $mailCandidate->Username   = $username;
            $mailCandidate->Password   = $psw;
            $mailCandidate->SMTPSecure = $SMTPSecure;
            $mailCandidate->Port       = $port;

            $mailCandidate->setFrom($carrearautomatedmailreply, 'Cypherox Technologies Pvt. Ltd.');
            $mailCandidate->addAddress($email, "$firstName $lastName");
            $mailCandidate->addReplyTo($carrearautomatedmailreply, 'Cypherox Technologies Pvt. Ltd.');

            $mailCandidate->isHTML(true);
            $mailCandidate->Subject = "Thank You for Submitting Your Resume";

             $svg = '   <tr>
            <td bgcolor="#f26e65" align="center" style="padding:25px 20px; color:#ffffff; font-size:22px; font-weight:bold;">
              <a href="https://www.cypherox.com/" target="_blank" style="display:inline-block; margin-bottom:15px;">
               <img src="https://cypherox.com/assets/img/email-logo.png" /> 
              </a>
              <div style="font-size:22px; line-height:28px; font-weight:bold;">Thank You for Your Application!</div>
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
                                        <p>Thank you for submitting your application through our career page. We sincerely appreciate your interest in joining <strong>Cypherox Technologies Pvt. Ltd.</strong></p>
                                        <p>Our HR team will review your application carefully and will reach out to you if your qualifications and experience align with our requirements.</p>
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

            $mailCandidate->AltBody = "Hello $firstName, Thank you for submitting your resume. We will contact you if your profile matches our openings.";

            $mailCandidate->send();

            echo "Thank you, $firstName! Your application has been submitted successfully.";

        }

    } catch (Exception $e) {
        http_response_code(500);
        exit("Mailer Error: {$mailHR->ErrorInfo}");
    }

} else {
    http_response_code(405);
    exit("Invalid request method.");
}
?>
