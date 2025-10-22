<?php
class User {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Register organizer
    public function registerOrganizer($data) {
        $query = "INSERT INTO organizer 
                  (email, password, first_name, last_name, linkedIn, NIC, phone, whatsapp, faculty, department, reg_no)
                  VALUES (:email, :password, :first_name, :last_name, :linkedIn, :NIC, :phone, :whatsapp, :faculty, :department, :reg_no)";
        
        $stmt = $this->conn->prepare($query);
        
        // Hash password
        $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':linkedIn', $data['linkedIn']);
        $stmt->bindParam(':NIC', $data['NIC']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':whatsapp', $data['whatsapp']);
        $stmt->bindParam(':faculty', $data['faculty']);
        $stmt->bindParam(':department', $data['department']);
        $stmt->bindParam(':reg_no', $data['reg_no']);
        
        return $stmt->execute();
    }
    
    // Login organizer
    public function loginOrganizer($email, $password) {
        $query = "SELECT * FROM organizer WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // Don't send password back
            return $user;
        }
        return false;
    }
    
    // Login admin
    public function loginAdmin($email, $password) {
        $query = "SELECT * FROM admin WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            unset($admin['password']);
            return $admin;
        }
        return false;
    }
}
?>