<?php
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = strip_tags(trim($_POST["name"] ?? ''));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    
    $profession = strip_tags(trim($_POST["profession"] ?? ''));
    
    $rating = trim($_POST["rating"] ?? '');
    
    $message = trim($_POST["message"] ?? '');

    // Check if any required field is empty or invalid
    if (empty($name) || empty($message) || empty($profession) || empty($rating) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Please complete all fields and try again.']);
        exit;
    }

    // Email configuration
    $recipient = "info@twigglecadstudio.lk";
    $subject = "New Customer Feedback from $name";

    // Build the email content
    $email_content = "You have received a new customer feedback submission.\n\n";
    $email_content .= "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Profession/Company: $profession\n";
    $email_content .= "Rating: $rating / 5\n\n";
    $email_content .= "Feedback Message:\n$message\n";

    // Build the email headers
    $email_headers = "From: $name <$email>";

    // Send the email
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Thank you! Your feedback has been successfully sent.']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Oops! Something went wrong and we couldn\'t send your feedback.']);
    }

} else {
    // Not a POST request
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'There was a problem with your submission, please try again.']);
}
?>
