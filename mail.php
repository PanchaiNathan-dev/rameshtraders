<?php
// Prevent any output before headers
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
    // Update this to your actual email address
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

    // Build the email headers.
    $email_headers = "From: Ramesh Trader Website <noreply@rameshtrader.com>\r\n";
    $email_headers .= "Reply-To: $name <$email>\r\n";
    $email_headers .= "MIME-Version: 1.0\r\n";
    $email_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Send the email.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Set a 200 (okay) response code.
        http_response_code(200);
        echo "Thank you! Your message has been sent successfully. We'll get back to you soon.";
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo "Sorry, something went wrong while sending your message. Please try again or contact us directly.";
    }
} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "Invalid request method. Please use the contact form.";
}
?>
