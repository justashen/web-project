<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once '../config/Database.php';
require_once '../models/Event.php';

$database = new Database();
$db = $database->getConnection();
$event = new Event($db);

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'list':
        $events = $event->getPublishedEvents();
        echo json_encode(['success' => true, 'events' => $events]);
        break;
    
    case 'trending':
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $events = $event->getTrendingEvents($limit);
        echo json_encode(['success' => true, 'events' => $events]);
        break;
    
    case 'recent':
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $events = $event->getRecentEvents($limit);
        echo json_encode(['success' => true, 'events' => $events]);
        break;
    
    case 'all':
        $filters = [
            'upcoming' => $_GET['upcoming'] ?? null,
            'past' => $_GET['past'] ?? null,
            'search' => $_GET['search'] ?? null
        ];
        $events = $event->getAllEvents(array_filter($filters));
        echo json_encode(['success' => true, 'events' => $events]);
        break;
    
    case 'view':
        $data = json_decode(file_get_contents("php://input"), true);
        if ($event->incrementViewCount($data['event_id'])) {
            echo json_encode(['success' => true, 'message' => 'View counted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error counting view']);
        }
        break;
        
    case 'like':
        $data = json_decode(file_get_contents("php://input"), true);
        $ip_address = $_SERVER['REMOTE_ADDR'];
        
        if ($event->likeEvent($data['event_id'], $ip_address)) {
            echo json_encode(['success' => true, 'message' => 'Event liked']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Already liked or error']);
        }
        break;
        
    case 'unlike':
        $data = json_decode(file_get_contents("php://input"), true);
        $ip_address = $_SERVER['REMOTE_ADDR'];
        
        if ($event->unlikeEvent($data['event_id'], $ip_address)) {
            echo json_encode(['success' => true, 'message' => 'Event unliked']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error unliking']);
        }
        break;
    
    case 'check-like':
        $event_id = $_GET['event_id'] ?? null;
        $ip_address = $_SERVER['REMOTE_ADDR'];
        
        if ($event_id) {
            $hasLiked = $event->hasLiked($event_id, $ip_address);
            echo json_encode(['success' => true, 'hasLiked' => $hasLiked]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Event ID required']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>