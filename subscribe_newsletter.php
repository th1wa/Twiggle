<?php
$response = array();

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['success'] = false;
        $response['message'] = "Valid email is required";
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    // Save to CSV
    $csv_file = 'newsletter_subscribers.csv';
    $file_exists = file_exists($csv_file);
    $fp = fopen($csv_file, 'a');
    if ($fp) {
        if (!$file_exists) {
            fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($fp, ['Subscription Date', 'Email']);
        }
        $subscription_date = date('Y-m-d H:i:s');
        fputcsv($fp, [$subscription_date, $email]);
        fclose($fp);
        
        $response['success'] = true;
        $response['message'] = "Thank you for subscribing!";
    } else {
        $response['success'] = false;
        $response['message'] = "Failed to save subscription. Please try again.";
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$response['success'] = false;
$response['message'] = "Invalid request method";
header('Content-Type: application/json');
echo json_encode($response);
?>
