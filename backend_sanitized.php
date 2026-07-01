<?php
header('Content-Type: application/json');

$author = isset($_POST['author']) ? trim($_POST['author']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';


if (empty($author)) {
    http_response_code(400);
    echo json_encode(['error' => 'Author name is required']);
    exit;
}

if (empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required']);
    exit;
}


$webhook_url = 'https://discord.com/api/webhooks/YOUR_WEBHOOK_ID/YOUR_WEBHOOK_TOKEN';

$data = [
    'content' => "**From:** $author\n**Message:** $message"
];


$ch = curl_init($webhook_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 204) {
    echo json_encode(['success' => 'Message sent!']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send message']);
}
?>