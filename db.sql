-- Create database
CREATE DATABASE blood_donation;

-- Create users table
USE blood_donation;

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL UNIQUE
);
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NULL,
    image_url VARCHAR(255),
    active TINYINT(1) NOT NULL DEFAULT 1,
    force_reset TINYINT(1) NOT NULL DEFAULT 0,
    role_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);
CREATE TABLE user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    dob DATE,
    gender ENUM('male', 'female') DEFAULT NULL,
    blood_type ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') DEFAULT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    failed_attempts INT NOT NULL DEFAULT 0,
    last_failed_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    otp_code VARCHAR(10) NOT NULL,
    otp_expiry DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
CREATE TABLE user_contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    telegram_chat_id VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
INSERT INTO user_contacts (user_id, telegram_chat_id) VALUES (1, '878514898'), (2, '878514898');
INSERT INTO roles (name) VALUES ('admin'), ('staff'), ('donor'), ('hospital');

CREATE TABLE assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    q1 VARCHAR(10),
    q2 VARCHAR(10),
    q3 VARCHAR(10),
    q4 VARCHAR(10),
    q5 VARCHAR(10),
    status VARCHAR(10),
    appointment_date DATE,
    create_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

SELECT telegram_chat_id FROM user_contacts WHERE user_id = 9;
select * from users;
select * from user_profiles;
select * from user_contacts;
select * from login_attempts;
select * from password_resets;
select * from roles;
select * from assessments;
update users set active = 0 where username = 'test';