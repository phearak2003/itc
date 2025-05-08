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
CREATE TABLE question_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);
INSERT INTO question_categories (name, description) VALUES
('General Health', 'Basic health check before donation'),
('Medical History', 'Questions about past and current medical conditions'),
('Infectious Diseases', 'Check for risk of disease transmission'),
('Travel History', 'Recent travel to areas with health risks'),
('Lifestyle Risk', 'Behavioral risk factors'),
('Donation History', 'History of previous donations'),
('Female-Specific', 'Special questions for female donors');
CREATE TABLE hospitals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact_number VARCHAR(20),
    telegram_chat_id VARCHAR(50),
    address TEXT NOT NULL,
    city VARCHAR(100),
    country VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE question_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);
CREATE TABLE assessment_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL,
    category_id INT NOT NULL,
    expected_answer VARCHAR(3),
    is_required BOOLEAN DEFAULT TRUE,
    order_no INT,
    FOREIGN KEY (category_id) REFERENCES question_categories(id) ON DELETE CASCADE
);
CREATE TABLE assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    is_pass TINYINT(1) NOT NULL DEFAULT 0,
    is_book_appointment TINYINT(1) NOT NULL DEFAULT 0,
    create_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE assessment_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    assessment_id INT NOT NULL,
    question_id INT NOT NULL,
    answer VARCHAR(3) NOT NULL,
    is_correct TINYINT(1) NOT NULL DEFAULT 0,
    FOREIGN KEY (assessment_id) REFERENCES assessments(id),
    FOREIGN KEY (question_id) REFERENCES assessment_questions(id)
);
CREATE TABLE donation_appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    hospital_id INT NOT NULL,
    assessment_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    status ENUM('Pending', 'Accepted', 'Completed', 'Cancelled', 'Expired') DEFAULT 'Pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (assessment_id) REFERENCES assessments(id),
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id)
);



INSERT INTO roles (name) VALUES ('admin'), ('staff'), ('donor'), ('hospital');
INSERT INTO user_contacts (user_id, telegram_chat_id) VALUES (1, '878514898'), (2, '878514898');

SELECT * FROM assessments;
SELECT * FROM assessment_details;
SELECT * FROM assessment_questions;
SELECT id FROM users WHERE username = 'test';
SELECT telegram_chat_id FROM user_contacts WHERE user_id = 9;
select * from users;
select * from user_profiles;
select * from user_contacts;
select * from login_attempts;
select * from password_resets;
select * from roles;
select * from assessments;
select * from question_categories;

update assessments set is_pass = 1 where id = 1;
update assessment_details set is_correct = 1 where id in (1, 5, 15, 17, 10, 6, 2, 9);

SELECT u.username, u.image_url, up.first_name, up.last_name, up.dob, up.gender, up.blood_type, uc.telegram_chat_id, r.name as role_name 
FROM users u JOIN user_profiles up ON u.id = up.user_id 
LEFT JOIN user_contacts uc ON u.id = uc.user_id 
JOIN roles r ON u.role_id = r.id 
WHERE u.id = 13;

drop table assessments;
