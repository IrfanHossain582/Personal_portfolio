<?php
// api/contact.php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    $data = [
        'name' => htmlspecialchars(strip_tags($name)),
        'email' => htmlspecialchars(strip_tags($email)),
        'message' => htmlspecialchars(strip_tags($message)),
        'status' => 'new'
    ];

    // Trigger Go High Level Webhook
    $webhook_url = 'https://services.leadconnectorhq.com/hooks/xnK9XgHdPIFUajSVVFfV/webhook-trigger/8c5f9b54-7070-4148-8a23-c72c49a4f19d';
    $webhook_ch = curl_init($webhook_url);
    curl_setopt($webhook_ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($webhook_ch, CURLOPT_POST, true);
    curl_setopt($webhook_ch, CURLOPT_POSTFIELDS, json_encode([
        'name' => $data['name'],
        'email' => $data['email'],
        'message' => $data['message']
    ]));
    curl_setopt($webhook_ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_exec($webhook_ch);


    $result = supabase_insert('contact_messages', $data);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send message. Try again later.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
