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
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    padding: 20px;
                }
                .email-container {
                    max-width: 600px;
                    margin: 0 auto;
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                    overflow: hidden;
                }
                .header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    padding: 40px 30px;
                    text-align: center;
                    color: white;
                }
                .header h1 {
                    font-size: 28px;
                    font-weight: 700;
                    margin-bottom: 10px;
                    letter-spacing: 0.5px;
                }
                .header p {
                    font-size: 14px;
                    opacity: 0.9;
                    font-weight: 300;
                }
                .icon-badge {
                    display: inline-block;
                    width: 60px;
                    height: 60px;
                    background: rgba(255,255,255,0.2);
                    border-radius: 50%;
                    margin-bottom: 15px;
                    font-size: 30px;
                    line-height: 60px;
                }
                .content {
                    padding: 40px 30px;
                }
                .intro {
                    font-size: 16px;
                    color: #333;
                    margin-bottom: 30px;
                    line-height: 1.6;
                }
                .details-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 20px;
                    margin: 30px 0;
                }
                .detail-item {
                    background: #f8f9ff;
                    padding: 20px;
                    border-radius: 8px;
                    border-left: 4px solid #667eea;
                }
                .detail-label {
                    font-size: 12px;
                    color: #667eea;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    margin-bottom: 8px;
                }
                .detail-value {
                    font-size: 16px;
                    color: #333;
                    font-weight: 600;
                    word-break: break-word;
                }
                .message-section {
                    background: #fff3cd;
                    border: 2px solid #ffc107;
                    border-radius: 8px;
                    padding: 20px;
                    margin: 25px 0;
                }
                .message-section .label {
                    font-size: 12px;
                    color: #856404;
                    font-weight: 700;
                    text-transform: uppercase;
                    margin-bottom: 10px;
                    display: block;
                }
                .message-section .value {
                    color: #333;
                    font-size: 14px;
                    line-height: 1.6;
                    white-space: pre-wrap;
                }
                .cta-section {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 25px;
                    border-radius: 8px;
                    margin: 30px 0;
                    text-align: center;
                }
                .cta-section p {
                    font-size: 14px;
                    margin-bottom: 15px;
                    opacity: 0.95;
                }
                .contact-info {
                    font-size: 13px;
                    color: #666;
                    padding: 20px;
                    background: #f5f5f5;
                    border-radius: 8px;
                    margin: 20px 0;
                }
                .contact-info strong {
                    display: block;
                    margin-bottom: 10px;
                    color: #333;
                }
                .footer {
                    background: #f5f5f5;
                    padding: 25px 30px;
                    text-align: center;
                    border-top: 1px solid #e0e0e0;
                }
                .footer p {
                    font-size: 12px;
                    color: #999;
                    margin: 5px 0;
                }
                .footer-brand {
                    color: #667eea;
                    font-weight: 700;
                    font-size: 13px;
                }
                .divider {
                    height: 1px;
                    background: #e0e0e0;
                    margin: 20px 0;
                }
                @media (max-width: 600px) {
                    .details-grid {
                        grid-template-columns: 1fr;
                    }
                    .header { padding: 30px 20px; }
                    .header h1 { font-size: 24px; }
                    .content { padding: 25px 20px; }
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header'>
                    <div class='icon-badge'>📋</div>
                    <h1>New Appointment Request</h1>
                    <p>From Twiggle CAD Studio</p>
                </div>
                
                <div class='content'>
                    <div class='intro'>
                        <strong style='color: #667eea; font-size: 18px;'>New appointment request received!</strong>
                        <p style='margin-top: 10px;'>A potential client has submitted an appointment request. Here are the details:</p>
                    </div>
                    
                    <div class='details-grid'>
                        <div class='detail-item'>
                            <div class='detail-label'>👤 Full Name</div>
                            <div class='detail-value'>" . htmlspecialchars($name) . "</div>
                        </div>
                        <div class='detail-item'>
                            <div class='detail-label'>📧 Email Address</div>
                            <div class='detail-value'>" . htmlspecialchars($email) . "</div>
                        </div>
                        <div class='detail-item'>
                            <div class='detail-label'>📱 Mobile Number</div>
                            <div class='detail-value'>" . htmlspecialchars($mobile) . "</div>
                        </div>
                        <div class='detail-item'>
                            <div class='detail-label'>🎯 Service Requested</div>
                            <div class='detail-value'>" . htmlspecialchars($service) . "</div>
                        </div>
                        <div class='detail-item'>
                            <div class='detail-label'>📅 Preferred Date</div>
                            <div class='detail-value'>" . htmlspecialchars($date) . "</div>
                        </div>
                        <div class='detail-item'>
                            <div class='detail-label'>⏰ Preferred Time</div>
                            <div class='detail-value'>" . htmlspecialchars($time) . "</div>
                        </div>
                    </div>
                    
                    " . (!empty($message) ? "<div class='message-section'>
                        <span class='label'>💬 Message</span>
                        <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
                    </div>" : "") . "
                    
                    <div class='cta-section'>
                        <p><strong>⚡ Action Required</strong></p>
                        <p>Please review this request and contact the client at the provided details to confirm their appointment.</p>
                    </div>
                    
                    <div class='contact-info'>
                        <strong>📞 Quick Contact Info:</strong>
                        Email: <a href='mailto:" . htmlspecialchars($email) . "' style='color: #667eea; text-decoration: none;'>" . htmlspecialchars($email) . "</a><br>
                        Phone: <a href='tel:" . preg_replace('/[^0-9\+]/', '', $mobile) . "' style='color: #667eea; text-decoration: none;'>" . htmlspecialchars($mobile) . "</a>
                    </div>
                </div>
                
                <div class='footer'>
                    <p><span class='footer-brand'>🏢 Twiggle CAD Studio</span></p>
                    <p>Professional CAD & Design Services</p>
                    <p style='margin-top: 15px; border-top: 1px solid #ddd; padding-top: 15px;'>
                        <strong>Contact & Address:</strong><br>
                        📍 58/19 A D.M Colomboge Mawatha, Kirulapana, Colombo 05<br>
                        📞 +94 76 070 8494<br>
                        📧 info@twigglecadstudio.lk
                    </p>
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
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    padding: 20px;
                }
                .email-container {
                    max-width: 600px;
                    margin: 0 auto;
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                    overflow: hidden;
                }
                .header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    padding: 40px 30px;
                    text-align: center;
                    color: white;
                }
                .header h1 {
                    font-size: 28px;
                    font-weight: 700;
                    margin-bottom: 10px;
                    letter-spacing: 0.5px;
                }
                .header p {
                    font-size: 14px;
                    opacity: 0.9;
                    font-weight: 300;
                }
                .icon-badge {
                    display: inline-block;
                    width: 60px;
                    height: 60px;
                    background: rgba(255,255,255,0.2);
                    border-radius: 50%;
                    margin-bottom: 15px;
                    font-size: 30px;
                    line-height: 60px;
                }
                .content {
                    padding: 40px 30px;
                }
                .greeting {
                    font-size: 18px;
                    color: #333;
                    margin-bottom: 5px;
                    font-weight: 600;
                }
                .subtitle {
                    font-size: 14px;
                    color: #666;
                    margin-bottom: 25px;
                    line-height: 1.6;
                }
                .appointment-box {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 30px;
                    border-radius: 10px;
                    margin: 25px 0;
                }
                .appointment-title {
                    font-size: 16px;
                    font-weight: 700;
                    margin-bottom: 20px;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    opacity: 0.95;
                }
                .appointment-details {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 20px;
                }
                .detail-item {
                    background: rgba(255,255,255,0.15);
                    padding: 15px;
                    border-radius: 8px;
                    backdrop-filter: blur(10px);
                }
                .detail-label {
                    font-size: 11px;
                    opacity: 0.85;
                    margin-bottom: 8px;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                .detail-value {
                    font-size: 16px;
                    font-weight: 700;
                    word-break: break-word;
                }
                .info-box {
                    background: #f0f4ff;
                    border-left: 4px solid #667eea;
                    padding: 20px;
                    margin: 25px 0;
                    border-radius: 8px;
                }
                .info-box strong {
                    display: block;
                    color: #667eea;
                    font-size: 13px;
                    text-transform: uppercase;
                    margin-bottom: 10px;
                    letter-spacing: 0.5px;
                }
                .info-box p {
                    font-size: 14px;
                    color: #333;
                    margin: 5px 0;
                    line-height: 1.6;
                }
                .contact-links {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 15px;
                    margin: 20px 0;
                }
                .contact-link {
                    background: #fff3cd;
                    padding: 15px;
                    text-align: center;
                    border-radius: 8px;
                    text-decoration: none;
                    color: #856404;
                    font-weight: 600;
                    font-size: 13px;
                    transition: all 0.3s ease;
                }
                .footer {
                    background: #f5f5f5;
                    padding: 30px;
                    text-align: center;
                    border-top: 1px solid #e0e0e0;
                }
                .footer-brand {
                    color: #667eea;
                    font-weight: 700;
                    font-size: 14px;
                    display: block;
                    margin-bottom: 10px;
                }
                .footer p {
                    font-size: 12px;
                    color: #999;
                    margin: 5px 0;
                    line-height: 1.6;
                }
                .success-msg {
                    background: #d4edda;
                    border: 2px solid #28a745;
                    color: #155724;
                    padding: 15px;
                    border-radius: 8px;
                    margin: 20px 0;
                    font-weight: 500;
                }
                @media (max-width: 600px) {
                    .appointment-details, .contact-links {
                        grid-template-columns: 1fr;
                    }
                    .header { padding: 30px 20px; }
                    .header h1 { font-size: 24px; }
                    .content { padding: 25px 20px; }
                    .appointment-box { padding: 20px; }
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header'>
                    <div class='icon-badge'>✅</div>
                    <h1>Request Received!</h1>
                    <p>Thank You for Choosing Twiggle CAD Studio</p>
                </div>
                
                <div class='content'>
                    <div class='greeting'>Hi " . htmlspecialchars($name) . ",</div>
                    <div class='subtitle'>
                        We've successfully received your appointment request! 🎉 Our team is reviewing your details and will contact you shortly to confirm your appointment.
                    </div>
                    
                    <div class='success-msg'>
                        ✓ Your appointment request has been submitted successfully
                    </div>
                    
                    <div class='appointment-box'>
                        <div class='appointment-title'>📋 Your Appointment Details</div>
                        <div class='appointment-details'>
                            <div class='detail-item'>
                                <div class='detail-label'>🎯 Service</div>
                                <div class='detail-value'>" . htmlspecialchars($service) . "</div>
                            </div>
                            <div class='detail-item'>
                                <div class='detail-label'>📅 Date</div>
                                <div class='detail-value'>" . htmlspecialchars($date) . "</div>
                            </div>
                            <div class='detail-item'>
                                <div class='detail-label'>⏰ Time</div>
                                <div class='detail-value'>" . htmlspecialchars($time) . "</div>
                            </div>
                            <div class='detail-item'>
                                <div class='detail-label'>👤 Name</div>
                                <div class='detail-value'>" . htmlspecialchars($name) . "</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='info-box'>
                        <strong>⏱️ What Happens Next?</strong>
                        <p>Our team will review your request and contact you within 24 hours to confirm the appointment and discuss any additional details.</p>
                    </div>
                    
                    <div class='info-box'>
                        <strong>📞 Contact Information on File</strong>
                        <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
                        <p><strong>Phone:</strong> " . htmlspecialchars($mobile) . "</p>
                    </div>
                    
                    <div class='contact-links'>
                        <a href='mailto:info@twigglecadstudio.lk' class='contact-link'>📧 Email Us</a>
                        <a href='tel:+94760708494' class='contact-link'>📞 Call Us</a>
                    </div>
                    
                    <div class='info-box' style='background: #f9f9f9; border-left: 4px solid #ffc107;'>
                        <strong>💡 In the Meantime</strong>
                        <p>Feel free to check out our portfolio and services on our website or call us directly at <strong>+94 76 070 8494</strong> if you have any questions!</p>
                    </div>
                </div>
                
                <div class='footer'>
                    <span class='footer-brand'>🏢 Twiggle CAD Studio</span>
                    <p style='margin-bottom: 15px;'>Professional CAD & Design Services</p>
                    <p><strong>📍 Address:</strong> 58/19 A D.M Colomboge Mawatha, Kirulapana, Colombo 05</p>
                    <p><strong>📞 Phone:</strong> +94 76 070 8494</p>
                    <p><strong>📧 Email:</strong> info@twigglecadstudio.lk</p>
                    <p style='margin-top: 15px; border-top: 1px solid #ddd; padding-top: 15px; font-size: 11px; color: #bbb;'>
                        © 2026 Twiggle CAD Studio. All rights reserved.
                    </p>
                </div>
            </div>
        </body>
    </html>
    ";
}
?>
