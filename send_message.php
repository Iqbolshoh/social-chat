<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./login/");
    exit;
}

include './config.php';
$query = new Database();

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];

$response = [
    'status' => '',
    'message' => ''
];

if (isset($_POST['content']) && !empty($_POST['content'])) {

    $message_text = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');

    $data = [
        'sender_id' => $sender_id,
        'receiver_id' => $receiver_id,
        'content' => $message_text,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $result = $query->insert('messages', $data);

    if (is_numeric($result)) {
        $new_message = [
            'id' => $result,
            'content' => $message_text,
            'created_at' => $data['created_at']
        ];

        $response['status'] = 'success';
        $response['message'] = 'Message sent successfully';
        $response['data'] = $new_message;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
