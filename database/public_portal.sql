-- ============================================================
-- Public Portal Add-on
-- Import this AFTER bloodbank.sql (and sample_data.sql, if used)
-- Adds: public sign up/login for everyone, blood requests linked
-- to a public account, and a "willing to donate" offer system.
-- ============================================================

-- ------------------------------------------------------------
-- 1. PUBLIC USERS (anyone can sign up here — not admin/staff)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS public_users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- 2. DONATION OFFERS (people who signed up to donate blood)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS donation_offers (
    offer_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    blood_group ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
    city VARCHAR(100),
    available_date DATE,
    message VARCHAR(255),
    status ENUM('Pending','Contacted','Completed') DEFAULT 'Pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES public_users(user_id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- 3. Link blood_requests to the public account that submitted it
--    (existing admin-created requests keep working — this column
--    is optional/NULL for those)
-- ------------------------------------------------------------
ALTER TABLE blood_requests
    ADD COLUMN user_id INT NULL AFTER request_id,
    ADD FOREIGN KEY (user_id) REFERENCES public_users(user_id) ON DELETE SET NULL;

ALTER TABLE blood_requests
    MODIFY hospital_id INT NULL,
    MODIFY doctor_name VARCHAR(100) NULL;
