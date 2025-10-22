<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include required files
require_once '../config/Database.php';
require_once '../models/EventRequest.php';
require_once '../controllers/EventRequestController.php';

$controller = new EventRequestController();
$method = $_SERVER['REQUEST_METHOD'];

// Get request ID if present
$request_id = isset($_GET['id']) ? intval($_GET['id']) : null;

try {
    switch ($method) {
        case 'GET':
            if ($request_id) {
                // Get single event request
                $response = $controller->getOne($request_id);
            } else {
                // Get all event requests with optional filters
                $filters = [
                    'status' => $_GET['status'] ?? null,
                    'organizer_id' => $_GET['organizer_id'] ?? null,
                    'search' => $_GET['search'] ?? null
                ];
                $response = $controller->getAll(array_filter($filters));
            }
            break;

        case 'POST':
            // Create new event request
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
                exit();
            }
            
            $response = $controller->create($data);
            break;

        case 'PUT':
            if (!$request_id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Request ID required']);
                exit();
            }

            $data = json_decode(file_get_contents("php://input"), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
                exit();
            }

            // Check if status update
            if (isset($data['status']) && count($data) === 1) {
                $response = $controller->updateStatus($request_id, $data['status']);
            } else {
                $response = $controller->update($request_id, $data);
            }
            break;

        case 'DELETE':
            if (!$request_id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Request ID required']);
                exit();
            }
            
            $response = $controller->delete($request_id);
            break;

        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit();
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?>