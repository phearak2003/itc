<?php
$mysqli = new mysqli("localhost", "root", "", "");

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Testing only
$mysqli->query("DROP DATABASE IF EXISTS blood_donation");

// Create database
$mysqli->query("CREATE DATABASE IF NOT EXISTS blood_donation");
$mysqli->select_db("blood_donation");

// Create tables
$schema = "
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

CREATE TABLE assessment_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL,
    category_id INT NOT NULL,
    expected_answer VARCHAR(3),
    is_required BOOLEAN DEFAULT TRUE,
    order_no INT,
    FOREIGN KEY (category_id) REFERENCES question_categories(id) ON DELETE CASCADE
);
INSERT INTO assessment_questions (question_text, category_id, expected_answer, is_required, order_no) VALUES
-- 1. General Health Questions (category_id = 1)
('Are you feeling well today?', 1, 'Yes', TRUE, 1),
('Do you have any cold, flu, or fever symptoms?', 1, 'No', TRUE, 2),
('Have you recently taken any medications?', 1, 'No', TRUE, 3),
('Do you have any allergies?', 1, 'No', TRUE, 4),

-- 2. Medical History (category_id = 2)
('Have you ever had heart disease, cancer, epilepsy, diabetes, or other chronic illnesses?', 2, 'No', TRUE, 5),
('Have you had any recent surgeries or major dental procedures?', 2, 'No', TRUE, 6),
('Have you received any vaccines or injections in the last few weeks?', 2, 'No', TRUE, 7),

-- 3. Infectious Diseases (category_id = 3)
('Have you ever tested positive for HIV/AIDS, hepatitis B or C, syphilis, or other blood-borne infections?', 3, 'No', TRUE, 8),
('Have you had jaundice or unexplained weight loss?', 3, 'No', TRUE, 9),

-- 4. Travel History (category_id = 4)
('Have you recently traveled to areas where malaria or other infectious diseases are common?', 4, 'No', TRUE, 10),

-- 5. Lifestyle and Risk Behavior (category_id = 5)
('Do you use or have you ever used intravenous drugs?', 5, 'No', TRUE, 11),
('Have you had unprotected sex with multiple partners or with someone who has HIV or other STDs?', 5, 'No', TRUE, 12),
('Have you ever received money or drugs in exchange for sex?', 5, 'No', TRUE, 13),

-- 6. Previous Donation History (category_id = 6)
('Have you donated blood in the last 3 months (for men) or 4 months (for women)?', 6, 'No', TRUE, 14),
('Have you ever had a reaction to a previous blood donation?', 6, 'No', TRUE, 15),

-- 7. Female-Specific Questions (category_id = 7)
('Are you currently pregnant or have you recently given birth?', 7, 'No', TRUE, 16),
('Are you breastfeeding?', 7, 'No', TRUE, 17);


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

CREATE TABLE donation_appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    hospital_id INT NOT NULL,
    assessment_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (assessment_id) REFERENCES assessments(id),
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id)
);

CREATE TABLE donation_appointment_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donation_appointment_id INT NOT NULL,
    status ENUM('Pending', 'Accepted', 'Completed', 'Rejected', 'Expired') DEFAULT 'Pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donation_appointment_id) REFERENCES donation_appointments(id)
);
";

if ($mysqli->multi_query($schema)) {
    do {
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->next_result());
    echo "✅ Schema created successfully.<br>";
} else {
    die("❌ Error creating schema: " . $mysqli->error);
}

// Insert default roles
$roles = ["admin", "staff", "donor", "hospital"];
foreach ($roles as $role) {
    $stmt = $mysqli->prepare("INSERT IGNORE INTO roles (name) VALUES (?)");
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $stmt->close();
}
echo "✅ Default roles inserted.<br>";

// Default admin data
$username = "admin";
$password = password_hash("Admin@123", PASSWORD_DEFAULT);
$image_url = "uploads/assets/default-user.png";
$role_name = "admin";

// Profile data
$first_name = "Pho";
$last_name = "Phearak";
$dob = "2000-01-01";
$gender = "male";
$blood_type = "A+";

// Contact data
$telegram_chat_id = "878514898";

// 1. Get role ID
$stmt = $mysqli->prepare("SELECT id FROM roles WHERE name = ?");
$stmt->bind_param("s", $role_name);
$stmt->execute();
$stmt->bind_result($role_id);
$stmt->fetch();
$stmt->close();

if (!$role_id) {
    die("❌ Role not found.");
}

// 2. Insert into users
$stmt = $mysqli->prepare("INSERT INTO users (username, password, image_url, role_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $username, $password, $image_url, $role_id);

if ($stmt->execute()) {
    $user_id = $stmt->insert_id;
    echo "✅ User created (ID: $user_id)<br>";
    $stmt->close();

    // 3. Insert into user_profiles
    $stmt = $mysqli->prepare("
        INSERT INTO user_profiles (user_id, first_name, last_name, dob, gender, blood_type)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isssss", $user_id, $first_name, $last_name, $dob, $gender, $blood_type);
    $stmt->execute();
    $stmt->close();
    echo "✅ User profile inserted.<br>";

    // 4. Insert into user_contacts
    $stmt = $mysqli->prepare("
        INSERT INTO user_contacts (user_id, telegram_chat_id)
        VALUES (?, ?)
    ");
    $stmt->bind_param("is", $user_id, $telegram_chat_id);
    $stmt->execute();
    $stmt->close();
    echo "✅ User contact inserted.<br>";
} else {
    echo "❌ Failed to insert user: " . $stmt->error;
    $stmt->close();
}
