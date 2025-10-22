-- Insert default admin
INSERT INTO admin (email, password, name) 
VALUES ('admin@gmail.com', '$2y$10$PAB.mRgnq4dfzOJJ0vLfGOJVLHT20bY7raQI7xvV7LJ6IrgwioSca', 'Admin');
-- Password is: 123456 (hashed with bcrypt)
?>