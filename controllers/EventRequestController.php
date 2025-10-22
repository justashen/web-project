<?php
class EventRequestController {
    private $db;
    private $eventRequest;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->eventRequest = new EventRequest($this->db);
    }

    // Create event request
    public function create($data) {
        // Validate input
        if (!isset($data['organizer_id']) || !isset($data['title']) || 
            !isset($data['venue']) || !isset($data['description']) ||
            !isset($data['starts_at']) || !isset($data['ends_at'])) {
            return $this->response(400, false, "Missing required fields");
        }

        // Set properties
        $this->eventRequest->organizer_id = $data['organizer_id'];
        $this->eventRequest->title = $data['title'];
        $this->eventRequest->venue = $data['venue'];
        $this->eventRequest->description = $data['description'];
        $this->eventRequest->starts_at = $data['starts_at'];
        $this->eventRequest->ends_at = $data['ends_at'];

        // Validate
        $validation = $this->eventRequest->validate();
        if ($validation !== true) {
            return $this->response(400, false, "Validation failed", ['errors' => $validation]);
        }

        // Create
        if ($this->eventRequest->create()) {
            return $this->response(201, true, "Event request created successfully", [
                'request_id' => $this->eventRequest->request_id
            ]);
        }

        return $this->response(500, false, "Failed to create event request");
    }

    // Get all event requests
    public function getAll($filters = []) {
        $stmt = $this->eventRequest->read($filters);
        $requests = $stmt->fetchAll();

        return $this->response(200, true, "Event requests retrieved successfully", [
            'count' => count($requests),
            'data' => $requests
        ]);
    }

    // Get single event request
    public function getOne($id) {
        $this->eventRequest->request_id = $id;
        $request = $this->eventRequest->readOne();

        if ($request) {
            return $this->response(200, true, "Event request retrieved successfully", [
                'data' => $request
            ]);
        }

        return $this->response(404, false, "Event request not found");
    }

    // Update event request
    public function update($id, $data) {
        $this->eventRequest->request_id = $id;

        // Check if exists
        if (!$this->eventRequest->readOne()) {
            return $this->response(404, false, "Event request not found");
        }

        // Set properties
        $this->eventRequest->title = $data['title'] ?? $this->eventRequest->title;
        $this->eventRequest->venue = $data['venue'] ?? $this->eventRequest->venue;
        $this->eventRequest->description = $data['description'] ?? $this->eventRequest->description;
        $this->eventRequest->starts_at = $data['starts_at'] ?? $this->eventRequest->starts_at;
        $this->eventRequest->ends_at = $data['ends_at'] ?? $this->eventRequest->ends_at;

        // Validate
        $validation = $this->eventRequest->validate();
        if ($validation !== true) {
            return $this->response(400, false, "Validation failed", ['errors' => $validation]);
        }

        // Update
        if ($this->eventRequest->update()) {
            return $this->response(200, true, "Event request updated successfully");
        }

        return $this->response(500, false, "Failed to update event request");
    }

    // Update status (admin only)
    public function updateStatus($id, $status) {
        $validStatuses = ['pending', 'approved', 'rejected', 'deleted'];
        
        if (!in_array($status, $validStatuses)) {
            return $this->response(400, false, "Invalid status");
        }

        $this->eventRequest->request_id = $id;

        // Check if exists
        if (!$this->eventRequest->readOne()) {
            return $this->response(404, false, "Event request not found");
        }

        $this->eventRequest->status = $status;

        if ($this->eventRequest->updateStatus()) {
            return $this->response(200, true, "Status updated successfully");
        }

        return $this->response(500, false, "Failed to update status");
    }

    // Delete event request
    public function delete($id) {
        $this->eventRequest->request_id = $id;

        // Check if exists
        if (!$this->eventRequest->readOne()) {
            return $this->response(404, false, "Event request not found");
        }

        if ($this->eventRequest->delete()) {
            return $this->response(200, true, "Event request deleted successfully");
        }

        return $this->response(500, false, "Failed to delete event request");
    }

    // Response helper
    private function response($code, $success, $message, $data = null) {
        http_response_code($code);
        $response = [
            'success' => $success,
            'message' => $message
        ];
        if ($data !== null) {
            $response = array_merge($response, $data);
        }
        return $response;
    }
}
?>