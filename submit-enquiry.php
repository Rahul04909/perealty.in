<?php
/**
 * Submit Enquiry AJAX Endpoint
 * Prime Edge Realty
 */

// Tell browser we return JSON
header('Content-Type: application/json; charset=utf-8');

// Load config and DB initialization helper
require_once __DIR__ . '/config.php';

$response = [
    'success' => false,
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request SAPI method.';
    echo json_encode($response);
    exit;
}

$clientName    = trim($_POST['client_name'] ?? '');
$clientEmail   = trim($_POST['client_email'] ?? '');
$clientPhone   = trim($_POST['client_phone'] ?? '');
$clientMessage = trim($_POST['client_message'] ?? '');
$projectId     = (int)($_POST['project_id'] ?? 0);
$projectTitle  = trim($_POST['project_title'] ?? '');

// Simple validations
if (empty($clientName) || empty($clientEmail) || empty($clientPhone) || empty($clientMessage)) {
    $response['message'] = 'Please fill out all required fields (Name, Email, Phone, Message).';
    echo json_encode($response);
    exit;
}

if (!filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Invalid email address configuration.';
    echo json_encode($response);
    exit;
}

// Enforce 10-digit Indian Mobile Number validation
$cleanPhone = preg_replace('/[^0-9]/', '', $clientPhone);
if (strlen($cleanPhone) !== 10 || !preg_match('/^[6-9][0-9]{9}$/', $cleanPhone)) {
    $response['message'] = 'Please enter a valid 10-digit Indian phone number (e.g. 9876543210).';
    echo json_encode($response);
    exit;
}
$clientPhone = $cleanPhone;

try {
    $db = db();
    
    // Insert ticket
    $stmt = $db->prepare("INSERT INTO `enquiries` 
        (`project_id`, `project_title`, `name`, `email`, `phone`, `message`, `status`) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
        
    $stmt->execute([
        $projectId > 0 ? $projectId : null,
        !empty($projectTitle) ? $projectTitle : 'General Inquiry',
        $clientName,
        $clientEmail,
        $clientPhone,
        $clientMessage,
        'New'
    ]);
    
    $response['success'] = true;
    $response['message'] = 'Your enquiry has been successfully registered. Our team will contact you shortly.';
} catch (\Exception $e) {
    $response['message'] = 'Database error occurred: ' . $e->getMessage();
}

echo json_encode($response);
exit;
