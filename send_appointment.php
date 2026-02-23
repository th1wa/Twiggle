<?php
// cPanel Mail Configuration
$recipient_email = "info@twigglecadstudio.lk";
$response = array();

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $service = trim($_POST['service'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $time = trim($_POST['time'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Basic validation
    $errors = array();
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($mobile)) {
        $errors[] = "Mobile number is required";
    }
    
    if (empty($service)) {
        $errors[] = "Service selection is required";
    }
    
    if (empty($date)) {
        $errors[] = "Date is required";
    }
    
    if (empty($time)) {
        $errors[] = "Time is required";
    }
    
    // If there are validation errors
    if (!empty($errors)) {
        $response['success'] = false;
        $response['message'] = implode(", ", $errors);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    // Prepare email content
    $subject = "New Appointment Request from " . $name;
    
    $email_body = build_appointment_email($name, $email, $mobile, $service, $date, $time, $message);
    
    // Email headers
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . $recipient_email . "\r\n";
    $headers .= "Reply-To: " . htmlspecialchars($email) . "\r\n";
    
    // Send email
    if (mail($recipient_email, $subject, $email_body, $headers)) {
        // Also send confirmation to user
        $user_subject = "Appointment Request Received - Twiggle CAD Studio";
        $user_body = build_confirmation_email($name, $mobile, $email, $service, $date, $time);
        
        $user_headers = "MIME-Version: 1.0\r\n";
        $user_headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $user_headers .= "From: " . $recipient_email . "\r\n";
        
        mail($email, $user_subject, $user_body, $user_headers);
        
        $response['success'] = true;
        $response['message'] = "Appointment request submitted successfully! We will contact you soon.";
    } else {
        $response['success'] = false;
        $response['message'] = "Failed to submit appointment request. Please try again.";
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$response['success'] = false;
$response['message'] = "Invalid request method";
header('Content-Type: application/json');
echo json_encode($response);

// Build appointment request email
function build_appointment_email($name, $email, $mobile, $service, $date, $time, $message) {
    return "
    <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #f5f5f5; padding: 15px; border-bottom: 2px solid #0d6efd; }
                .content { padding: 20px; }
                .field { margin-bottom: 15px; }
                .field-label { font-weight: bold; color: #333; }
                .field-value { color: #666; margin-top: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Appointment Request</h2>
                </div>
                <div class='content'>
                    <div class='field'>
                        <div class='field-label'>Name:</div>
                        <div class='field-value'>" . htmlspecialchars($name) . "</div>
                    </div>
                    <div class='field'>
                        <div class='field-label'>Email:</div>
                        <div class='field-value'>" . htmlspecialchars($email) . "</div>
                    </div>
                    <div class='field'>
                        <div class='field-label'>Mobile:</div>
                        <div class='field-value'>" . htmlspecialchars($mobile) . "</div>
                    </div>
                    <div class='field'>
                        <div class='field-label'>Service:</div>
                        <div class='field-value'>" . htmlspecialchars($service) . "</div>
                    </div>
                    <div class='field'>
                        <div class='field-label'>Preferred Date:</div>
                        <div class='field-value'>" . htmlspecialchars($date) . "</div>
                    </div>
                    <div class='field'>
                        <div class='field-label'>Preferred Time:</div>
                        <div class='field-value'>" . htmlspecialchars($time) . "</div>
                    </div>
                    <div class='field'>
                        <div class='field-label'>Message:</div>
                        <div class='field-value'>" . nl2br(htmlspecialchars($message)) . "</div>
                    </div>
                </div>
            </div>
        </body>
    </html>
    ";
}

// Build confirmation email
function build_confirmation_email($name, $mobile, $email, $service, $date, $time) {
    return "
    <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #f5f5f5; padding: 15px; border-bottom: 2px solid #0d6efd; }
                .content { padding: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Appointment Request Received</h2>
                </div>
                <div class='content'>
                    <p>Dear " . htmlspecialchars($name) . ",</p>
                    <p>Thank you for choosing Twiggle CAD Studio! We have received your appointment request and will contact you shortly at " . htmlspecialchars($mobile) . " or " . htmlspecialchars($email) . ".</p>
                    <p><strong>Your Appointment Details:</strong></p>
                    <ul>
                        <li><strong>Service:</strong> " . htmlspecialchars($service) . "</li>
                        <li><strong>Date:</strong> " . htmlspecialchars($date) . "</li>
                        <li><strong>Time:</strong> " . htmlspecialchars($time) . "</li>
                    </ul>
                    <p>Best regards,<br><strong>Twiggle CAD Studio Team</strong></p>
                </div>
            </div>
        </body>
    </html>
    ";
}
?>