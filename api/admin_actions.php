<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, PUT");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

require_once '../config/Database.php';
require_once '../models/EventRequest.php';
require_once '../models/Event.php';

// Check if admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$database = new Database();
$db = $database->getConnection();
$eventRequest = new EventRequest($db);
$event = new Event($db);

$action = $_GET['action'] ?? '';
$data = json_decode(file_get_contents("php://input"), true);

switch ($action) {
    case 'approve':
        $request_id = $data['request_id'];
        $eventRequest->request_id = $request_id;
        $eventRequest->status = 'approved';
        
        if ($eventRequest->updateStatus()) {
            // Create event from approved request
            if ($event->createFromRequest($request_id)) {
                echo json_encode(['success' => true, 'message' => 'Request approved and event created']);
            } else {
                echo json_encode(['success' => true, 'message' => 'Request approved but event creation failed']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to approve request']);
        }
        break;
        
    case 'reject':
        $request_id = $data['request_id'];
        $eventRequest->request_id = $request_id;
        $eventRequest->status = 'rejected';
        
        if ($eventRequest->updateStatus()) {
            echo json_encode(['success' => true, 'message' => 'Request rejected']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to reject request']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>