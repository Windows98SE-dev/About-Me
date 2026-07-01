<?php
header('Content-Type: application/json');

$author = isset($_POST['author']) ? trim($_POST['author']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if ($author === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Author name is required']);
    exit;
}

if ($message === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required']);
    exit;
}

$webhook_url = 'https://discord.com/api/webhooks/1521847871073288344/hSgnISUq73rOk5mTgbiSpRCy9djDOv7uoS9Ohkw_0i89GSUy4stjLjruM8u3ei21sg2o';

$payload = json_encode([
    'content' => "**From:** {$author}\n**Message:** {$message}"
]);

if ($payload === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to encode message']);
    exit;
}

$ch = curl_init($webhook_url);
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_RETURNTRANSFER => true,
]);

$response = curl_exec($ch);

if ($response === false) {
    $error = curl_error($ch);
    curl_close($ch);
    http_response_code(500);
    echo json_encode(['error' => 'Webhook request failed: ' . $error]);
    exit;
}

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 204) {
    echo json_encode(['success' => 'Message sent!']);
    exit;
}

http_response_code(500);
echo json_encode([
    'error' => 'Failed to send message to Discord',
    'status' => $http_code,
]);