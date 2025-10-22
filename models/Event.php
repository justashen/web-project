<?php
class Event {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Get published events (public)
    public function getPublishedEvents() {
        $query = "SELECT e.*, CONCAT(o.first_name, ' ', o.last_name) as organizer_name
                  FROM events e
                  LEFT JOIN event_request er ON e.request_id = er.request_id
                  LEFT JOIN organizer o ON er.organizer_id = o.organizer_id
                  WHERE e.status = 'published'
                  ORDER BY e.starts_at ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Create event from approved request
    public function createFromRequest($request_id) {
        // Get request details
        $query = "SELECT * FROM event_request WHERE request_id = :request_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':request_id', $request_id);
        $stmt->execute();
        $request = $stmt->fetch();
        
        if (!$request) return false;
        
        // Create event
        $query = "INSERT INTO events (request_id, title, venue, description, starts_at, ends_at, status)
                  VALUES (:request_id, :title, :venue, :description, :starts_at, :ends_at, 'published')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':request_id', $request['request_id']);
        $stmt->bindParam(':title', $request['title']);
        $stmt->bindParam(':venue', $request['venue']);
        $stmt->bindParam(':description', $request['description']);
        $stmt->bindParam(':starts_at', $request['starts_at']);
        $stmt->bindParam(':ends_at', $request['ends_at']);
        
        return $stmt->execute();
    }
    
    // Like event
    public function likeEvent($event_id, $ip_address) {
        $query = "INSERT INTO event_likes (event_id, ip_address) VALUES (:event_id, :ip_address)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->bindParam(':ip_address', $ip_address);
        
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return false; // Already liked
        }
    }
    
    // Unlike event
    public function unlikeEvent($event_id, $ip_address) {
        $query = "DELETE FROM event_likes WHERE event_id = :event_id AND ip_address = :ip_address";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->bindParam(':ip_address', $ip_address);
        return $stmt->execute();
    }
}
?>