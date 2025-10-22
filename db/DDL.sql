
CREATE DATABASE japura_events;
USE japura_events;


CREATE TABLE organizer (
    organizer_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    linkedIn VARCHAR(255),
    NIC VARCHAR(20) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    whatsapp VARCHAR(20) NOT NULL,
    faculty VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    reg_no VARCHAR(50) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE admin (
    admin_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255)
);


CREATE TABLE event_request (
    request_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    organizer_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    venue VARCHAR(255) NOT NULL,
    description VARCHAR(1000) NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'deleted') NOT NULL DEFAULT 'pending',
    FOREIGN KEY (organizer_id) REFERENCES organizer(organizer_id),
    starts_at DATETIME NOT NULL,
    ends_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

     -- indexes for performance optimization
    INDEX idx_status (status),
    INDEX idx_starts_at (starts_at),
    INDEX idx_ends_at (ends_at),
    INDEX idx_title (title)
);


CREATE TABLE events (
    event_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    venue VARCHAR(255) NOT NULL,
    description VARCHAR(1000) NOT NULL,
    status ENUM('draft', 'published', 'deleted', 'canceled') NOT NULL DEFAULT 'draft',
    like_count INT NOT NULL DEFAULT 0,
    UNIQUE KEY events_request_id (request_id),
    FOREIGN KEY (request_id) REFERENCES event_request(request_id),
    starts_at DATETIME NOT NULL,
    ends_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- indexes for performance optimization
    INDEX idx_status (status),
    INDEX idx_starts_at (starts_at),
    INDEX idx_ends_at (ends_at),
    INDEX idx_title (title)
);


CREATE TABLE event_likes (
    event_id INT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    liked_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (event_id, ip_address),
    FOREIGN KEY (event_id) REFERENCES events(event_id),
    INDEX idx_liked_at (liked_at)
);

