<?php
// Development version - simulates email sending and logs messages
header('Content-Type: application/json');

// Only process POST requests.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form fields and remove whitespace.
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";
    $message = trim($_POST["message"]);

    // Check that required data was sent to the mailer.
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Please fill in all required fields with valid information.";
        exit;
    }

    // Set the recipient email address.
    $recipient = "sales@rameshtrader.com";

    // Set the email subject.
    $subject = "New Contact Form Submission - Ramesh Trader Website";

    // Build the email content.
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

    // Create emails directory if it doesn't exist
    $emails_dir = __DIR__ . '/emails';
    if (!file_exists($emails_dir)) {
        mkdir($emails_dir, 0777, true);
    }

    // Save email to file (for development testing)
    $filename = $emails_dir . '/email_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.txt';
    $log_content = "TO: $recipient\n";
    $log_content .= "SUBJECT: $subject\n";
    $log_content .= "FROM: Ramesh Trader Website <noreply@rameshtrader.com>\n";
    $log_content .= "REPLY-TO: $name <$email>\n";
    $log_content .= "DATE: " . date('Y-m-d H:i:s') . "\n";
    $log_content .= "\n" . str_repeat("=", 50) . "\n\n";
    $log_content .= $email_content;
    
    // Simulate email sending (always successful in development)
    $email_sent = file_put_contents($filename, $log_content);
    
    if ($email_sent !== false) {
        // Success
        http_response_code(200);
        echo "Thank you! Your message has been received successfully. We'll get back to you soon.";
    } else {
        // Error saving file
        http_response_code(500);
        echo "Sorry, something went wrong while processing your message. Please try again.";
    }
} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "Invalid request method. Please use the contact form.";
}
?>