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
                    background: #f5f5f5;
                    padding: 20px;
                    color: #333;
                }
                .email-container {
                    max-width: 650px;
                    margin: 0 auto;
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 8px 24px rgba(0, 188, 212, 0.12);
                    overflow: hidden;
                }
                .header {
                    background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
                    padding: 50px 30px;
                    text-align: center;
                    color: white;
                    position: relative;
                }
                .header::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 1200 120\"><path d=\"M0,50 Q250,0 500,50 T1000,50 L1200,120 L0,120\" fill=\"rgba(255,255,255,0.1)\"/></svg>');
                    background-size: cover;
                    bottom: -1px;
                    opacity: 0.5;
                }
                .header-content {
                    position: relative;
                    z-index: 1;
                }
                .header h1 {
                    font-size: 32px;
                    font-weight: 700;
                    margin-bottom: 5px;
                    letter-spacing: -0.5px;
                }
                .header p {
                    font-size: 14px;
                    opacity: 0.95;
                    font-weight: 300;
                    margin: 0;
                }
                .icon-badge {
                    display: inline-block;
                    width: 70px;
                    height: 70px;
                    background: rgba(255,255,255,0.25);
                    border-radius: 50%;
                    margin-bottom: 20px;
                    font-size: 36px;
                    line-height: 70px;
                    backdrop-filter: blur(10px);
                }
                .content {
                    padding: 45px 35px;
                }
                .intro {
                    font-size: 16px;
                    color: #1E1E1E;
                    margin-bottom: 30px;
                    line-height: 1.6;
                    font-weight: 500;
                }
                .details-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 15px;
                    margin: 35px 0;
                }
                .detail-item {
                    background: linear-gradient(135deg, #f0f9fb 0%, #e0f7fa 100%);
                    padding: 18px;
                    border-radius: 10px;
                    border-left: 4px solid #00BCD4;
                    transition: all 0.3s ease;
                }
                .detail-item:hover {
                    box-shadow: 0 4px 12px rgba(0, 188, 212, 0.15);
                    transform: translateY(-2px);
                }
                .detail-label {
                    font-size: 11px;
                    color: #0097A7;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    margin-bottom: 8px;
                }
                .detail-value {
                    font-size: 15px;
                    color: #1E1E1E;
                    font-weight: 600;
                    word-break: break-word;
                }
                .message-section {
                    background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
                    border: 2px solid #fbc02d;
                    border-radius: 10px;
                    padding: 22px;
                    margin: 30px 0;
                }
                .message-section .label {
                    font-size: 11px;
                    color: #F57F17;
                    font-weight: 700;
                    text-transform: uppercase;
                    margin-bottom: 12px;
                    display: block;
                    letter-spacing: 0.5px;
                }
                .message-section .value {
                    color: #1E1E1E;
                    font-size: 14px;
                    line-height: 1.6;
                    white-space: pre-wrap;
                }
                .cta-section {
                    background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
                    color: white;
                    padding: 30px 25px;
                    border-radius: 10px;
                    margin: 35px 0;
                    text-align: center;
                }
                .cta-button {
                    display: inline-block;
                    background: white;
                    color: #0097A7;
                    padding: 12px 30px;
                    border-radius: 6px;
                    text-decoration: none;
                    font-weight: 600;
                    margin-top: 15px;
                    font-size: 14px;
                    transition: all 0.3s ease;
                }
                .cta-button:hover {
                    background: #f0f9fb;
                    transform: translateY(-2px);
                }
                .contact-info {
                    font-size: 13px;
                    color: #666;
                    padding: 22px;
                    background: linear-gradient(135deg, #f0f9fb 0%, #e0f7fa 100%);
                    border-radius: 10px;
                    margin: 25px 0;
                    border-left: 4px solid #00BCD4;
                }
                .contact-info strong {
                    display: block;
                    margin-bottom: 10px;
                    color: #0097A7;
                    font-weight: 600;
                }
                .contact-link {
                    color: #00BCD4;
                    text-decoration: none;
                    font-weight: 600;
                }
                .contact-link:hover {
                    text-decoration: underline;
                }
                .footer {
                    background: linear-gradient(135deg, #1E1E1E 0%, #2c2c2c 100%);
                    padding: 30px 35px;
                    text-align: center;
                    color: #bbb;
                }
                .footer p {
                    font-size: 12px;
                    color: #999;
                    margin: 6px 0;
                    line-height: 1.6;
                }
                .footer-brand {
                    color: #00BCD4;
                    font-weight: 700;
                    font-size: 14px;
                    display: block;
                    margin-bottom: 8px;
                }
                .divider {
                    height: 1px;
                    background: rgba(0, 188, 212, 0.2);
                    margin: 20px 0;
                }
                @media (max-width: 600px) {
                    .details-grid {
                        grid-template-columns: 1fr;
                    }
                    .header { padding: 35px 20px; }
                    .header h1 { font-size: 26px; }
                    .content { padding: 30px 20px; }
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header'>
                    <div class='header-content'>
                        <div class='icon-badge'>📋</div>
                        <h1>New Appointment Request</h1>
                        <p>From Twiggle CAD Studio</p>
                    </div>
                </div>
                
                <div class='content'>
                    <div class='intro'>
                        <strong style='color: #0097A7; font-size: 18px;'>A potential client has submitted an appointment request!</strong>
                        <p style='margin-top: 10px; color: #666;'>Here are the complete details for your review:</p>
                    </div>
                    
                    <div class='details-grid'>
                        <div class='detail-item'>
                            <div class='detail-label'>👤 Full Name</div>
                            <div class='detail-value'>" . htmlspecialchars($name) . "</div>
                        </div>
                        <div class='detail-item'>
                            <div class='detail-label'>📧 Email Address</div>
                            <div class='detail-value'><a href='mailto:" . htmlspecialchars($email) . "' class='contact-link'>" . htmlspecialchars($email) . "</a></div>
                        </div>
                        <div class='detail-item'>
                            <div class='detail-label'>📱 Phone Number</div>
                            <div class='detail-value'><a href='tel:" . preg_replace('/[^0-9\+]/', '', $mobile) . "' class='contact-link'>" . htmlspecialchars($mobile) . "</a></div>
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
                        <span class='label'>💬 Client Message</span>
                        <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
                    </div>" : "") . "
                    
                    <div class='cta-section'>
                        <strong style='font-size: 16px;'>⚡ Action Required</strong>
                        <p style='margin: 10px 0 0 0; font-size: 14px;'>Please review this request and contact the client to confirm their appointment as soon as possible.</p>
                    </div>
                    
                    <div class='contact-info'>
                        <strong>📞 Quick Contact Information:</strong>
                        ✉️ Email: <a href='mailto:" . htmlspecialchars($email) . "' class='contact-link'>" . htmlspecialchars($email) . "</a><br>
                        📱 Phone: <a href='tel:" . preg_replace('/[^0-9\+]/', '', $mobile) . "' class='contact-link'>" . htmlspecialchars($mobile) . "</a>
                    </div>
                </div>
                
                <div class='footer'>
                    <span class='footer-brand'>🏢 Twiggle CAD Studio</span>
                    <p>Professional CAD & Design Services</p>
                    <div class='divider'></div>
                    <p><strong>📍 Address:</strong><br>58/19 A D.M Colomboge Mawatha, Kirulapana, Colombo 05</p>
                    <p><strong>📞 Phone:</strong> <a href='tel:+94760708494' class='contact-link'>+94 76 070 8494</a></p>
                    <p><strong>📧 Email:</strong> <a href='mailto:info@twigglecadstudio.lk' class='contact-link'>info@twigglecadstudio.lk</a></p>
                    <p style='margin-top: 15px; font-size: 11px;'>© 2026 Twiggle CAD Studio. All rights reserved.</p>
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
                    background: #f5f5f5;
                    padding: 20px;
                    color: #1E1E1E;
                }
                .email-container {
                    max-width: 650px;
                    margin: 0 auto;
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 8px 24px rgba(0, 188, 212, 0.12);
                    overflow: hidden;
                }
                .header {
                    background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
                    padding: 50px 30px;
                    text-align: center;
                    color: white;
                    position: relative;
                }
                .header::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 1200 120\"><path d=\"M0,50 Q250,0 500,50 T1000,50 L1200,120 L0,120\" fill=\"rgba(255,255,255,0.1)\"/></svg>');
                    background-size: cover;
                    bottom: -1px;
                    opacity: 0.5;
                }
                .header-content {
                    position: relative;
                    z-index: 1;
                }
                .header h1 {
                    font-size: 32px;
                    font-weight: 700;
                    margin-bottom: 5px;
                    letter-spacing: -0.5px;
                }
                .header p {
                    font-size: 14px;
                    opacity: 0.95;
                    font-weight: 300;
                    margin: 0;
                }
                .icon-badge {
                    display: inline-block;
                    width: 70px;
                    height: 70px;
                    background: rgba(255,255,255,0.25);
                    border-radius: 50%;
                    margin-bottom: 20px;
                    font-size: 36px;
                    line-height: 70px;
                    backdrop-filter: blur(10px);
                }
                .content {
                    padding: 45px 35px;
                }
                .greeting {
                    font-size: 18px;
                    color: #1E1E1E;
                    margin-bottom: 8px;
                    font-weight: 600;
                }
                .subtitle {
                    font-size: 14px;
                    color: #666;
                    margin-bottom: 30px;
                    line-height: 1.7;
                }
                .success-badge {
                    display: inline-block;
                    background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%);
                    color: white;
                    padding: 10px 20px;
                    border-radius: 25px;
                    font-size: 12px;
                    font-weight: 600;
                    margin-bottom: 25px;
                }
                .appointment-box {
                    background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
                    color: white;
                    padding: 35px 30px;
                    border-radius: 12px;
                    margin: 30px 0;
                    position: relative;
                    overflow: hidden;
                }
                .appointment-box::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    right: 0;
                    width: 200px;
                    height: 200px;
                    background: rgba(255,255,255,0.1);
                    border-radius: 50%;
                    transform: translate(50%, -50%);
                }
                .appointment-title {
                    font-size: 16px;
                    font-weight: 700;
                    margin-bottom: 25px;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    opacity: 0.95;
                    position: relative;
                    z-index: 1;
                }
                .appointment-details {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 20px;
                    position: relative;
                    z-index: 1;
                }
                .detail-item {
                    background: rgba(255,255,255,0.15);
                    padding: 16px;
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
                    background: linear-gradient(135deg, #f0f9fb 0%, #e0f7fa 100%);
                    border-left: 4px solid #00BCD4;
                    padding: 22px;
                    margin: 25px 0;
                    border-radius: 10px;
                }
                .info-box strong {
                    display: block;
                    color: #0097A7;
                    font-size: 13px;
                    text-transform: uppercase;
                    margin-bottom: 12px;
                    letter-spacing: 0.5px;
                    font-weight: 600;
                }
                .info-box p {
                    font-size: 14px;
                    color: #333;
                    margin: 6px 0;
                    line-height: 1.7;
                }
                .contact-links {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 15px;
                    margin: 25px 0;
                }
                .contact-link {
                    background: linear-gradient(135deg, #00BCD4 0%, #0097A7 100%);
                    padding: 15px;
                    text-align: center;
                    border-radius: 8px;
                    text-decoration: none;
                    color: white;
                    font-weight: 600;
                    font-size: 13px;
                    transition: all 0.3s ease;
                    display: block;
                }
                .contact-link:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(0, 188, 212, 0.3);
                }
                .footer {
                    background: linear-gradient(135deg, #1E1E1E 0%, #2c2c2c 100%);
                    padding: 35px 30px;
                    text-align: center;
                    color: #999;
                }
                .footer-brand {
                    color: #00BCD4;
                    font-weight: 700;
                    font-size: 14px;
                    display: block;
                    margin-bottom: 10px;
                }
                .footer p {
                    font-size: 12px;
                    color: #999;
                    margin: 6px 0;
                    line-height: 1.7;
                }
                .footer a {
                    color: #00BCD4;
                    text-decoration: none;
                    font-weight: 600;
                }
                .footer a:hover {
                    text-decoration: underline;
                }
                .divider {
                    height: 1px;
                    background: rgba(0, 188, 212, 0.2);
                    margin: 20px 0;
                }
                @media (max-width: 600px) {
                    .appointment-details, .contact-links {
                        grid-template-columns: 1fr;
                    }
                    .header { padding: 35px 20px; }
                    .header h1 { font-size: 26px; }
                    .content { padding: 30px 20px; }
                    .appointment-box { padding: 25px 20px; }
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='header'>
                    <div class='header-content'>
                        <div class='icon-badge'>✅</div>
                        <h1>Appointment Confirmed!</h1>
                        <p>Thank You for Choosing Twiggle CAD Studio</p>
                    </div>
                </div>
                
                <div class='content'>
                    <div class='greeting'>Hi " . htmlspecialchars($name) . ",</div>
                    <div class='subtitle'>
                        We've successfully received your appointment request! 🎉 Our team is reviewing your details and will contact you shortly to confirm your appointment and discuss your project requirements.
                    </div>
                    
                    <div class='success-badge'>✓ REQUEST RECEIVED & CONFIRMED</div>
                    
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
                        <p>Our team will review your request and contact you within 24 hours to confirm the appointment time and discuss your project in detail. We'll reach out via your preferred communication method.</p>
                    </div>
                    
                    <div class='info-box'>
                        <strong>📞 Your Contact Information</strong>
                        <p>📧 Email: " . htmlspecialchars($email) . "<br>
                        📱 Phone: " . htmlspecialchars($mobile) . "</p>
                    </div>
                    
                    <div class='contact-links'>
                        <a href='mailto:info@twigglecadstudio.lk' class='contact-link'>📧 Email Support</a>
                        <a href='tel:+94760708494' class='contact-link'>📞 Call Us Now</a>
                    </div>
                    
                    <div class='info-box' style='background: linear-gradient(135deg, #fff8e1 0%, #fffde7 100%); border-left: 4px solid #fbc02d;'>
                        <strong style='color: #F57F17;'>💡 In the Meantime</strong>
                        <p>Feel free to explore our <a href='#' style='color: #00BCD4; text-decoration: none;'>portfolio</a> or check out more about our <a href='#' style='color: #00BCD4; text-decoration: none;'>services</a>. If you have any urgent questions, don't hesitate to reach out!</p>
                    </div>
                </div>
                
                <div class='footer'>
                    <span class='footer-brand'>🏢 Twiggle CAD Studio</span>
                    <p>Professional CAD & Design Services</p>
                    <div class='divider'></div>
                    <p><strong>📍 Address:</strong><br>58/19 A D.M Colomboge Mawatha, Kirulapana, Colombo 05</p>
                    <p><strong>📞 Phone:</strong> <a href='tel:+94760708494'>+94 76 070 8494</a></p>
                    <p><strong>📧 Email:</strong> <a href='mailto:info@twigglecadstudio.lk'>info@twigglecadstudio.lk</a></p>
                    <p style='margin-top: 15px; font-size: 11px;'>© 2026 Twiggle CAD Studio. All rights reserved.</p>
                </div>
            </div>
        </body>
    </html>
    ";
}
?>
