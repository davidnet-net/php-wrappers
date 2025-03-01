<?php

// Set the response header to JSON
header('Content-Type: application/json');

// Check if the request is from the allowed domain
$allowedDomain = 'auth.davidnet.net';
if ($_SERVER['HTTP_REFERER'] !== 'https://' . $allowedDomain) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized request']);
    exit;
}

// Retrieve the input data (POST)
$inputData = json_decode(file_get_contents("php://input"), true);

// Check if all required fields are set
if (!isset($inputData['to']) || !isset($inputData['subject']) || !isset($inputData['message'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields: to, subject, and message are required']);
    exit;
}

// Extract email details
$to = $inputData['to'];
$subject = $inputData['subject'];
$message = $inputData['message'];

// Call the MailTo function
if (MailTo($to, $subject, $message)) {
    echo json_encode(['status' => 'success', 'message' => 'Email sent successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to send email']);
}

// MailTo function definition
function MailTo($To, $Subject, $Message) {
    $from = 'noreply@davidnet.net';  // Default 'From' address
    $headers = 'From: ' . $from . "\r\n" .
               'Reply-To: ' . $from . "\r\n" .
               'X-Priority: 1\r\n' .
               'X-Mailer: PHP/' . phpversion() . "\r\n" .
               'MIME-Version: 1.0' . "\r\n" .
               'Content-Type: text/html; charset=UTF-8' . "\r\n" .
               'Importance: Normal' . "\r\n";

    return mail($To, $Subject, $Message, $headers); // Send email
}

?>
`
