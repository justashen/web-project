<?php 
class EventRequest {
    private $conn;
    private $table = 'event_request';

    public $request_id;
    public $organizer_id;
    public $title;
    public $venue;
    public $description;
    public $status;
    public $starts_at;
    public $ends_at;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create event request
    public function create() {
        $query = "INSERT INTO " . $this->table . "
                SET organizer_id = :organizer_id,
                    title = :title,
                    venue = :venue,
                    description = :description,
                    starts_at = :starts_at,
                    ends_at = :ends_at";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->venue = htmlspecialchars(strip_tags($this->venue));
        $this->description = htmlspecialchars(strip_tags($this->description));

        // Bind parameters
        $stmt->bindParam(':organizer_id', $this->organizer_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':venue', $this->venue);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':starts_at', $this->starts_at);
        $stmt->bindParam(':ends_at', $this->ends_at);

        if($stmt->execute()) {
            $this->request_id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Read all event requests
    public function read($filters = []) {
        $query = "SELECT 
                    er.*,
                    CONCAT(o.first_name, ' ', o.last_name) as organizer_name,
                    o.email as organizer_email
                FROM " . $this->table . " er
                LEFT JOIN organizer o ON er.organizer_id = o.organizer_id
                WHERE 1=1";

        // Add filters
        if (!empty($filters['status'])) {
            $query .= " AND er.status = :status";
        }
        if (!empty($filters['organizer_id'])) {
            $query .= " AND er.organizer_id = :organizer_id";
        }
        if (!empty($filters['search'])) {
            $query .= " AND (er.title LIKE :search OR er.description LIKE :search)";
        }

        $query .= " ORDER BY er.created_at DESC";

        $stmt = $this->conn->prepare($query);

        // Bind filter parameters
        if (!empty($filters['status'])) {
            $stmt->bindParam(':status', $filters['status']);
        }
        if (!empty($filters['organizer_id'])) {
            $stmt->bindParam(':organizer_id', $filters['organizer_id']);
        }
        if (!empty($filters['search'])) {
            $search = "%" . $filters['search'] . "%";
            $stmt->bindParam(':search', $search);
        }

        $stmt->execute();
        return $stmt;
    }

    // Read single event request
    public function readOne() {
        $query = "SELECT 
                    er.*,
                    CONCAT(o.first_name, ' ', o.last_name) as organizer_name,
                    o.email as organizer_email,
                    o.phone as organizer_phone
                FROM " . $this->table . " er
                LEFT JOIN organizer o ON er.organizer_id = o.organizer_id
                WHERE er.request_id = :request_id
                LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':request_id', $this->request_id);
        $stmt->execute();

        $row = $stmt->fetch();

        if($row) {
            $this->organizer_id = $row['organizer_id'];
            $this->title = $row['title'];
            $this->venue = $row['venue'];
            $this->description = $row['description'];
            $this->status = $row['status'];
            $this->starts_at = $row['starts_at'];
            $this->ends_at = $row['ends_at'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return $row;
        }
        return false;
    }

    // Update event request
    public function update() {
        $query = "UPDATE " . $this->table . "
                SET title = :title,
                    venue = :venue,
                    description = :description,
                    starts_at = :starts_at,
                    ends_at = :ends_at
                WHERE request_id = :request_id";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->venue = htmlspecialchars(strip_tags($this->venue));
        $this->description = htmlspecialchars(strip_tags($this->description));

        // Bind parameters
        $stmt->bindParam(':request_id', $this->request_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':venue', $this->venue);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':starts_at', $this->starts_at);
        $stmt->bindParam(':ends_at', $this->ends_at);

        return $stmt->execute();
    }

    // Update status (for admin approval/rejection)
    public function updateStatus() {
        $query = "UPDATE " . $this->table . "
                SET status = :status
                WHERE request_id = :request_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':request_id', $this->request_id);
        $stmt->bindParam(':status', $this->status);

        return $stmt->execute();
    }

    // Delete event request (soft delete)
    public function delete() {
        $query = "UPDATE " . $this->table . "
                SET status = 'deleted'
                WHERE request_id = :request_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':request_id', $this->request_id);

        return $stmt->execute();
    }

    // Validation
    public function validate() {
        $errors = [];

        if (empty($this->title) || strlen($this->title) > 255) {
            $errors[] = "Title is required and must be less than 255 characters";
        }

        if (empty($this->venue) || strlen($this->venue) > 255) {
            $errors[] = "Venue is required and must be less than 255 characters";
        }

        if (empty($this->description) || strlen($this->description) > 1000) {
            $errors[] = "Description is required and must be less than 1000 characters";
        }

        if (empty($this->starts_at)) {
            $errors[] = "Start date and time is required";
        }

        if (empty($this->ends_at)) {
            $errors[] = "End date and time is required";
        }

        if (!empty($this->starts_at) && !empty($this->ends_at)) {
            $start = strtotime($this->starts_at);
            $end = strtotime($this->ends_at);
            $now = time();

            if ($start < $now) {
                $errors[] = "Start date must be in the future";
            }

            if ($end <= $start) {
                $errors[] = "End date must be after start date";
            }
        }

        return empty($errors) ? true : $errors;
    }
}
?>