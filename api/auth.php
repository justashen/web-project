<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

require_once '../config/Database.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$data = json_decode(file_get_contents("php://input"), true);
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'register':
        if ($user->registerOrganizer($data)) {
            echo json_encode(['success' => true, 'message' => 'Registration successful']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Registration failed']);
        }
        break;
        
    case 'login':
        $result = $user->loginOrganizer($data['email'], $data['password']);
        if ($result) {
            $_SESSION['user_id'] = $result['organizer_id'];
            $_SESSION['user_type'] = 'organizer';
            $_SESSION['user_name'] = $result['first_name'] . ' ' . $result['last_name'];
            echo json_encode(['success' => true, 'user' => $result, 'user_type' => 'organizer']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        }
        break;
        
    case 'admin-login':
        $result = $user->loginAdmin($data['email'], $data['password']);
        if ($result) {
            $_SESSION['user_id'] = $result['admin_id'];
            $_SESSION['user_type'] = 'admin';
            $_SESSION['user_name'] = $result['name'];
            echo json_encode(['success' => true, 'user' => $result, 'user_type' => 'admin']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid admin credentials']);
        }
        break;
        
    case 'logout':
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
        break;
        
    case 'check-session':
        if (isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => true,
                'logged_in' => true,
                'user_type' => $_SESSION['user_type'],
                'user_id' => $_SESSION['user_id'],
                'user_name' => $_SESSION['user_name']
            ]);
        } else {
            echo json_encode(['success' => true, 'logged_in' => false]);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>