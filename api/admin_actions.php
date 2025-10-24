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
    
    case 'edit':
        // Admin can edit any event request
        $request_id = $data['request_id'];
        $eventRequest->request_id = $request_id;
        
        // Load existing data
        if (!$eventRequest->readOne()) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Request not found']);
            exit();
        }
        
        // Update fields
        $eventRequest->title = $data['title'] ?? $eventRequest->title;
        $eventRequest->venue = $data['venue'] ?? $eventRequest->venue;
        $eventRequest->description = $data['description'] ?? $eventRequest->description;
        $eventRequest->thumbnail = $data['thumbnail'] ?? $eventRequest->thumbnail;
        $eventRequest->starts_at = $data['starts_at'] ?? $eventRequest->starts_at;
        $eventRequest->ends_at = $data['ends_at'] ?? $eventRequest->ends_at;
        $eventRequest->status = $data['status'] ?? $eventRequest->status;
        
        // Validate
        $validation = $eventRequest->validate();
        if ($validation !== true) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Validation failed', 'errors' => $validation]);
            exit();
        }
        
        // Update
        if ($eventRequest->adminUpdate()) {
            // If event is approved and exists, update the published event too
            if ($eventRequest->status === 'approved') {
                $event->updateFromRequest($request_id);
            }
            echo json_encode(['success' => true, 'message' => 'Event request updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update request']);
        }
        break;
    
    case 'change-status':
        // Admin can change status at any time
        $request_id = $data['request_id'];
        $new_status = $data['status'];
        
        $valid_statuses = ['pending', 'approved', 'rejected', 'deleted'];
        if (!in_array($new_status, $valid_statuses)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid status']);
            exit();
        }
        
        $eventRequest->request_id = $request_id;
        $requestData = $eventRequest->readOne();
        
        if (!$requestData) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Request not found']);
            exit();
        }
        
        $old_status = $requestData['status'];
        $eventRequest->status = $new_status;
        
        if ($eventRequest->updateStatus()) {
            // Handle status change logic
            if ($new_status === 'approved' && $old_status !== 'approved') {
                // Create or update event
                $event->createFromRequest($request_id);
            } elseif ($new_status === 'approved' && $old_status === 'approved') {
                // Just update the existing event
                $event->updateFromRequest($request_id);
            }
            
            echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
        break;
    
    case 'delete':
        // Soft delete event request
        $request_id = $data['request_id'];
        $eventRequest->request_id = $request_id;
        
        if (!$eventRequest->readOne()) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Request not found']);
            exit();
        }
        
        if ($eventRequest->delete()) {
            echo json_encode(['success' => true, 'message' => 'Request deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete request']);
        }
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>