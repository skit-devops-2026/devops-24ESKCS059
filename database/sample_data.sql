-- ============================================================
-- Sample Data for Blood Bank Management System
-- Import AFTER bloodbank.sql (run this second).
-- Gives you enough data to demo every screen: donors, hospitals,
-- stock, collections, issues, and requests in mixed statuses.
-- ============================================================


-- ------------------------------------------------------------
-- Hospitals
-- ------------------------------------------------------------
INSERT INTO hospitals (hospital_name, registration_number, contact_person, mobile, email, address, city, state) VALUES
('City General Hospital', 'REG-CGH-1001', 'Dr. Ramesh Iyer', '9820011223', 'contact@citygeneral.example', '12 MG Road', 'Jaipur', 'Rajasthan'),
('Sunrise Multispeciality', 'REG-SUN-2002', 'Dr. Neha Sharma', '9911223344', 'info@sunrisehosp.example', '45 Civil Lines', 'Jaipur', 'Rajasthan'),
('Lifeline Medical Center', 'REG-LMC-3003', 'Dr. Arjun Verma', '9765432109', 'admin@lifelinemed.example', '78 Station Road', 'Ajmer', 'Rajasthan'),
('St. Mary Hospital', 'REG-SMH-4004', 'Sr. Anna Thomas', '9877001122', 'reception@stmary.example', '5 Church Street', 'Udaipur', 'Rajasthan');

-- ------------------------------------------------------------
-- Donors (mix of blood groups, cities, availability)
-- ------------------------------------------------------------
INSERT INTO donors (name, gender, dob, age, blood_group, weight, mobile, email, address, city, state, last_donation_date, medical_status, availability_status) VALUES
('Rohit Sharma', 'Male', '1995-04-12', 31, 'O+', 72.5, '9001100110', 'rohit.s@example.com', '10 Park Street', 'Jaipur', 'Rajasthan', '2026-05-10', 'Fit', 'Available'),
('Priya Nair', 'Female', '1998-09-23', 27, 'A+', 58.0, '9002200220', 'priya.n@example.com', '22 Lake View', 'Jaipur', 'Rajasthan', '2026-06-01', 'Fit', 'Available'),
('Aman Gupta', 'Male', '1990-01-05', 36, 'B+', 80.0, '9003300330', 'aman.g@example.com', '7 Green Avenue', 'Ajmer', 'Rajasthan', '2026-04-20', 'Fit', 'Not Available'),
('Sneha Reddy', 'Female', '2000-11-30', 25, 'AB+', 55.5, '9004400440', 'sneha.r@example.com', '3 Hill Road', 'Udaipur', 'Rajasthan', NULL, 'Fit', 'Available'),
('Vikram Singh', 'Male', '1988-06-18', 38, 'O-', 76.0, '9005500550', 'vikram.s@example.com', '15 Fort Road', 'Jaipur', 'Rajasthan', '2026-03-15', 'Fit', 'Available'),
('Kavita Joshi', 'Female', '1993-02-14', 33, 'A-', 60.0, '9006600660', 'kavita.j@example.com', '9 Rose Colony', 'Jodhpur', 'Rajasthan', NULL, 'Fit', 'Available'),
('Manoj Kumar', 'Male', '1985-08-09', 40, 'B-', 82.0, '9007700770', 'manoj.k@example.com', '31 Sector 5', 'Jaipur', 'Rajasthan', '2026-01-25', 'Fit', 'Not Available'),
('Anjali Mehta', 'Female', '1997-12-01', 28, 'AB-', 52.0, '9008800880', 'anjali.m@example.com', '18 Model Town', 'Ajmer', 'Rajasthan', NULL, 'Fit', 'Available'),
('Rahul Verma', 'Male', '1992-07-22', 34, 'O+', 74.0, '9009900990', 'rahul.v@example.com', '27 New Colony', 'Jaipur', 'Rajasthan', '2026-06-15', 'Fit', 'Available'),
('Divya Pillai', 'Female', '1999-03-17', 27, 'A+', 57.0, '9010011001', 'divya.p@example.com', '4 Sunrise Apartments', 'Udaipur', 'Rajasthan', NULL, 'Fit', 'Available');

-- ------------------------------------------------------------
-- Blood Stock (overwrite the zeroed defaults from bloodbank.sql)
-- ------------------------------------------------------------
UPDATE blood_stock SET units_available = 18 WHERE blood_group = 'A+';
UPDATE blood_stock SET units_available = 4  WHERE blood_group = 'A-';
UPDATE blood_stock SET units_available = 12 WHERE blood_group = 'B+';
UPDATE blood_stock SET units_available = 3  WHERE blood_group = 'B-';
UPDATE blood_stock SET units_available = 6  WHERE blood_group = 'AB+';
UPDATE blood_stock SET units_available = 2  WHERE blood_group = 'AB-';
UPDATE blood_stock SET units_available = 22 WHERE blood_group = 'O+';
UPDATE blood_stock SET units_available = 5  WHERE blood_group = 'O-';

-- ------------------------------------------------------------
-- Blood Collection records (tie back to donor_id 1-10 above)
-- ------------------------------------------------------------
INSERT INTO blood_collection (donor_id, blood_group, units_collected, collection_date, expiry_date, staff_name) VALUES
(1, 'O+', 1, '2026-05-10', '2026-06-21', 'Nurse Kavita Rao'),
(2, 'A+', 1, '2026-06-01', '2026-07-13', 'Nurse Kavita Rao'),
(3, 'B+', 1, '2026-04-20', '2026-06-01', 'Nurse Suresh Patil'),
(5, 'O-', 1, '2026-03-15', '2026-04-26', 'Nurse Suresh Patil'),
(7, 'B-', 1, '2026-01-25', '2026-03-08', 'Nurse Kavita Rao'),
(9, 'O+', 1, '2026-06-15', '2026-07-27', 'Nurse Meena Iyer');

-- ------------------------------------------------------------
-- Blood Issue records
-- ------------------------------------------------------------
INSERT INTO blood_issue (patient_name, blood_group, units_issued, hospital_id, issue_date, approved_by, remarks) VALUES
('Suresh Patil', 'O+', 2, 1, '2026-06-20', 'System Administrator', 'Emergency surgery'),
('Meena Kulkarni', 'A+', 1, 2, '2026-06-25', 'System Administrator', 'Scheduled transfusion'),
('Ramesh Yadav', 'B+', 1, 3, '2026-07-02', 'System Administrator', 'Accident case');

-- ------------------------------------------------------------
-- Blood Requests (mixed statuses so the dashboard/list looks real)
-- ------------------------------------------------------------
INSERT INTO blood_requests (patient_name, age, gender, blood_group, required_units, hospital_id, doctor_name, mobile, emergency_level, request_date, status) VALUES
('Geeta Devi', 45, 'Female', 'O+', 2, 1, 'Dr. Ramesh Iyer', '9111122223', 'Critical', '2026-07-20', 'Pending'),
('Ashok Kumar', 52, 'Male', 'A+', 1, 2, 'Dr. Neha Sharma', '9222233334', 'Urgent', '2026-07-18', 'Approved'),
('Farah Khan', 29, 'Female', 'B-', 1, 3, 'Dr. Arjun Verma', '9333344445', 'Normal', '2026-07-15', 'Rejected'),
('Deepak Chauhan', 61, 'Male', 'AB+', 2, 4, 'Sr. Anna Thomas', '9444455556', 'Urgent', '2026-07-22', 'Pending'),
('Nisha Agarwal', 34, 'Female', 'O-', 1, 1, 'Dr. Ramesh Iyer', '9555566667', 'Critical', '2026-07-24', 'Pending');

-- ------------------------------------------------------------
-- Notifications (so the bell icon / notifications page isn't empty)
-- ------------------------------------------------------------
INSERT INTO notifications (type, message, is_read) VALUES
('New Request', 'New Critical request for 2 unit(s) of O+ (Geeta Devi).', 0),
('Low Stock', 'AB- stock is low: 2 units remaining.', 0),
('Low Stock', 'B- stock is low: 3 units remaining.', 0),
('Request Approved', 'Request for Ashok Kumar approved.', 1),
('Request Rejected', 'Request for Farah Khan was rejected.', 1);
