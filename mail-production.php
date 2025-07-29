<?php
/**
 * Production Email Handler using SMTP
 * 
 * To use this file:
 * 1. Download PHPMailer from: https://github.com/PHPMailer/PHPMailer
 * 2. Extract to a 'vendor' folder in your project
 * 3. Update the SMTP settings below with your email provider details
 * 4. Change the form action to 'mail-production.php'
 */

// Uncomment these lines when you have PHPMailer installed
/*
require 'vendor/autoload.php'; // If using Composer
// OR
require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
*/

header('Content-Type: application/json');

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form fields and remove whitespace
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";
    $message = trim($_POST["message"]);

    // Check that required data was sent
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please fill in all required fields with valid information.";
        exit;
    }

    // SMTP Configuration - UPDATE THESE WITH YOUR EMAIL PROVIDER SETTINGS
    $smtp_host = 'smtp.gmail.com';  // Gmail SMTP server
    $smtp_username = 'your-email@gmail.com';  // Your Gmail address
    $smtp_password = 'your-app-password';     // Your Gmail app password (not regular password)
    $smtp_port = 587;
    $smtp_encryption = 'tls'; // Will be converted to PHPMailer::ENCRYPTION_STARTTLS when PHPMailer is available
    
    // Email settings
    $recipient = "sales@rameshtrader.com";
    $subject = "New Contact Form Submission - Ramesh Trader Website";
    
    // Build email content
    $email_content = "You have received a new message from the Ramesh Trader website contact form.\n\n";
    $email_content .= "Contact Details:\n";
    $email_content .= "================\n";
    $email_content .= "Name: $name\n";
    $email_content .= "Email: $email\n";
    
    if (!empty($phone)) {
        $email_content .= "Phone: $phone\n";
    }
    
    $email_content .= "Submitted: " . date('Y-m-d H:i:s') . "\n";
    $email_content .= "\nMessage:\n";
    $email_content .= "========\n";
    $email_content .= "$message\n";
    $email_content .= "\n";
    $email_content .= "---\n";
    $email_content .= "This message was sent from the Ramesh Trader website contact form.\n";

    // Uncomment this section when PHPMailer is installed
    /*
    try {
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP();
        $mail->Host       = $smtp_host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtp_username;
        $mail->Password   = $smtp_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $smtp_port;

        // Recipients
        $mail->setFrom($smtp_username, 'Ramesh Trader Website');
        $mail->addAddress($recipient, 'Ramesh Trader');
        $mail->addReplyTo($email, $name);

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $email_content;

        $mail->send();
        
        http_response_code(200);
        echo "Thank you! Your message has been sent successfully. We'll get back to you soon.";
        
    } catch (Exception $e) {
        http_response_code(500);
        echo "Sorry, something went wrong while sending your message. Please try again or contact us directly.";
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }
    */
    
    // Temporary fallback until PHPMailer is set up
    http_response_code(500);
    echo "Email system is not yet configured for production. Please use the development version for testing.";
    
} else {
    http_response_code(403);
    echo "Invalid request method. Please use the contact form.";
}
?>

<!--
PRODUCTION SETUP INSTRUCTIONS:

1. Download PHPMailer:
   - Go to: https://github.com/PHPMailer/PHPMailer/releases
   - Download the latest version
   - Extract to a 'vendor' folder in your project

2. For Gmail SMTP:
   - Enable 2-factor authentication on your Gmail account
   - Generate an "App Password" (not your regular password)
   - Use these settings:
     * Host: smtp.gmail.com
     * Port: 587
     * Encryption: STARTTLS
     * Username: your-email@gmail.com
     * Password: your-16-character-app-password

3. For other email providers:
   - Outlook/Hotmail: smtp-mail.outlook.com, port 587
   - Yahoo: smtp.mail.yahoo.com, port 587
   - Custom hosting: Check with your hosting provider

4. Update the form action:
   - Change from 'mail-dev.php' to 'mail-production.php'

5. Security considerations:
   - Store SMTP credentials in environment variables
   - Use SSL encryption where possible
   - Implement rate limiting to prevent spam
   - Add CAPTCHA for additional protection
-->