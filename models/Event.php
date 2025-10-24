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
    
    // Get trending events (based on likes and views)
    public function getTrendingEvents($limit = 10) {
        $query = "SELECT e.*, CONCAT(o.first_name, ' ', o.last_name) as organizer_name,
                  (e.like_count * 2 + e.view_count) as trending_score
                  FROM events e
                  LEFT JOIN event_request er ON e.request_id = er.request_id
                  LEFT JOIN organizer o ON er.organizer_id = o.organizer_id
                  WHERE e.status = 'published'
                  AND e.starts_at >= NOW()
                  ORDER BY trending_score DESC, e.created_at DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Get recently added events
    public function getRecentEvents($limit = 10) {
        $query = "SELECT e.*, CONCAT(o.first_name, ' ', o.last_name) as organizer_name
                  FROM events e
                  LEFT JOIN event_request er ON e.request_id = er.request_id
                  LEFT JOIN organizer o ON er.organizer_id = o.organizer_id
                  WHERE e.status = 'published'
                  ORDER BY e.created_at DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Get all events with optional filters
    public function getAllEvents($filters = []) {
        $query = "SELECT e.*, CONCAT(o.first_name, ' ', o.last_name) as organizer_name
                  FROM events e
                  LEFT JOIN event_request er ON e.request_id = er.request_id
                  LEFT JOIN organizer o ON er.organizer_id = o.organizer_id
                  WHERE e.status = 'published'";
        
        if (!empty($filters['upcoming'])) {
            $query .= " AND e.starts_at >= NOW()";
        }
        
        if (!empty($filters['past'])) {
            $query .= " AND e.ends_at < NOW()";
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (e.title LIKE :search OR e.description LIKE :search OR e.venue LIKE :search)";
        }
        
        $query .= " ORDER BY e.starts_at DESC";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($filters['search'])) {
            $search = "%" . $filters['search'] . "%";
            $stmt->bindParam(':search', $search);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Increment view count
    public function incrementViewCount($event_id) {
        $query = "UPDATE events SET view_count = view_count + 1 WHERE event_id = :event_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':event_id', $event_id);
        return $stmt->execute();
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
        $query = "INSERT INTO events (request_id, title, venue, description, thumbnail, starts_at, ends_at, status)
                  VALUES (:request_id, :title, :venue, :description, :thumbnail, :starts_at, :ends_at, 'published')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':request_id', $request['request_id']);
        $stmt->bindParam(':title', $request['title']);
        $stmt->bindParam(':venue', $request['venue']);
        $stmt->bindParam(':description', $request['description']);
        $stmt->bindParam(':thumbnail', $request['thumbnail']);
        $stmt->bindParam(':starts_at', $request['starts_at']);
        $stmt->bindParam(':ends_at', $request['ends_at']);
        
        return $stmt->execute();
    }
    
    // Update event (sync with event request changes)
    public function updateFromRequest($request_id) {
        // Get request details
        $query = "SELECT * FROM event_request WHERE request_id = :request_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':request_id', $request_id);
        $stmt->execute();
        $request = $stmt->fetch();
        
        if (!$request) return false;
        
        // Update event
        $query = "UPDATE events 
                  SET title = :title, 
                      venue = :venue, 
                      description = :description,
                      thumbnail = :thumbnail,
                      starts_at = :starts_at, 
                      ends_at = :ends_at
                  WHERE request_id = :request_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':request_id', $request['request_id']);
        $stmt->bindParam(':title', $request['title']);
        $stmt->bindParam(':venue', $request['venue']);
        $stmt->bindParam(':description', $request['description']);
        $stmt->bindParam(':thumbnail', $request['thumbnail']);
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
    
    // Check if user has liked event
    public function hasLiked($event_id, $ip_address) {
        $query = "SELECT COUNT(*) as count FROM event_likes WHERE event_id = :event_id AND ip_address = :ip_address";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->bindParam(':ip_address', $ip_address);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
}
?>