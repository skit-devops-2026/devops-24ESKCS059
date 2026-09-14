-- ============================================================
-- Blood Bank Management System - Database Schema
-- Import this file via phpMyAdmin or:
--   mysql -u root -p < bloodbank.sql
-- ============================================================


-- ------------------------------------------------------------
-- 1. ADMIN
-- ------------------------------------------------------------
CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Default admin login: username = admin / password = Admin@123
INSERT INTO admin (username, password, full_name, email)
VALUES ('admin', '$2b$10$jGxNtzMltpCxwsNRPcOkzec/UtrZI.CoH80LNfiIRZqRMp7dKl6aq', 'System Administrator', 'admin@bloodbank.local');
-- This hash is a real, working bcrypt hash for "Admin@123" — no extra step needed.

-- ------------------------------------------------------------
-- 2. HOSPITALS  (created before donors/requests since both reference it)
-- ------------------------------------------------------------
CREATE TABLE hospitals (
    hospital_id INT AUTO_INCREMENT PRIMARY KEY,
    hospital_name VARCHAR(150) NOT NULL,
    registration_number VARCHAR(100) NOT NULL UNIQUE,
    contact_person VARCHAR(100),
    mobile VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    address VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- 3. DONORS
-- ------------------------------------------------------------
CREATE TABLE donors (
    donor_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    gender ENUM('Male','Female','Other') NOT NULL,
    dob DATE NOT NULL,
    age INT NOT NULL,
    blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
    weight DECIMAL(5,2),
    mobile VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    address VARCHAR(255),
    city VARCHAR(100),
    state VARCHAR(100),
    last_donation_date DATE DEFAULT NULL,
    medical_status VARCHAR(100) DEFAULT 'Fit',
    availability_status ENUM('Available','Not Available') DEFAULT 'Available',
    photo VARCHAR(255) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_blood_group (blood_group),
    INDEX idx_city (city)
);

-- ------------------------------------------------------------
-- 4. BLOOD STOCK  (one row per blood group)
-- ------------------------------------------------------------
CREATE TABLE blood_stock (
    stock_id INT AUTO_INCREMENT PRIMARY KEY,
    blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL UNIQUE,
    units_available INT NOT NULL DEFAULT 0,
    low_stock_threshold INT NOT NULL DEFAULT 5,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO blood_stock (blood_group, units_available) VALUES
('A+',0), ('A-',0), ('B+',0), ('B-',0),
('AB+',0), ('AB-',0), ('O+',0), ('O-',0);

-- ------------------------------------------------------------
-- 5. BLOOD COLLECTION  (donations coming IN)
-- ------------------------------------------------------------
CREATE TABLE blood_collection (
    collection_id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
    units_collected INT NOT NULL DEFAULT 1,
    collection_date DATE NOT NULL,
    expiry_date DATE NOT NULL,
    staff_name VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES donors(donor_id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- 6. BLOOD ISSUE  (units going OUT to hospitals/patients)
-- ------------------------------------------------------------
CREATE TABLE blood_issue (
    issue_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(100) NOT NULL,
    blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
    units_issued INT NOT NULL DEFAULT 1,
    hospital_id INT,
    issue_date DATE NOT NULL,
    approved_by VARCHAR(100),
    remarks VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(hospital_id) ON DELETE SET NULL
);

-- ------------------------------------------------------------
-- 7. BLOOD REQUESTS
-- ------------------------------------------------------------
CREATE TABLE blood_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(100) NOT NULL,
    age INT,
    gender ENUM('Male','Female','Other'),
    blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
    required_units INT NOT NULL DEFAULT 1,
    hospital_id INT,
    doctor_name VARCHAR(100),
    mobile VARCHAR(20) NOT NULL,
    emergency_level ENUM('Normal','Urgent','Critical') DEFAULT 'Normal',
    request_date DATE NOT NULL,
    status ENUM('Pending','Approved','Rejected') DEFAULT 'Pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(hospital_id) ON DELETE SET NULL
);

-- ------------------------------------------------------------
-- 8. USERS  (hospital / staff logins - separate from admin)
-- ------------------------------------------------------------
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('hospital','staff') NOT NULL,
    hospital_id INT DEFAULT NULL,
    full_name VARCHAR(100),
    email VARCHAR(100),
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(hospital_id) ON DELETE SET NULL
);

-- ------------------------------------------------------------
-- 9. NOTIFICATIONS
-- ------------------------------------------------------------
CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('New Request','Low Stock','Expiry Alert','Request Approved','Request Rejected','Donation Reminder') NOT NULL,
    message VARCHAR(255) NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- 10. REPORTS  (log of generated reports, for audit trail)
-- ------------------------------------------------------------
CREATE TABLE reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    report_type VARCHAR(100) NOT NULL,
    generated_by VARCHAR(100),
    date_from DATE,
    date_to DATE,
    generated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- 11. LOGIN HISTORY
-- ------------------------------------------------------------
CREATE TABLE login_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    role VARCHAR(20) NOT NULL,
    login_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(50),
    status ENUM('Success','Failed') DEFAULT 'Success'
);
