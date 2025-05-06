<?php
$mysqli = new mysqli("localhost", "root", "", "");

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Testing only
$mysqli->query("DROP DATABASE blood_donation");

// Create database
$mysqli->query("CREATE DATABASE IF NOT EXISTS blood_donation");
$mysqli->select_db("blood_donation");

// Create tables
$schema = "
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS users (
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

CREATE TABLE IF NOT EXISTS user_profiles (
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

CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    failed_attempts INT NOT NULL DEFAULT 0,
    last_failed_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    otp_code VARCHAR(10) NOT NULL,
    otp_expiry DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS user_contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    telegram_chat_id VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS assessments (
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
