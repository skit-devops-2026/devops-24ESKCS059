<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$type = clean($conn, $_GET['type'] ?? '');
$date_from = clean($conn, $_GET['date_from'] ?? date('Y-m-01'));
$date_to = clean($conn, $_GET['date_to'] ?? date('Y-m-d'));

// Map report type -> [SQL, headers]
$map = [
    'donor' => [
        "SELECT donor_id, name, gender, age, blood_group, mobile, email, city, availability_status FROM donors WHERE created_at BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59'",
        ['ID','Name','Gender','Age','Blood Group','Mobile','Email','City','Availability']
    ],
    'stock' => [
        "SELECT blood_group, units_available, low_stock_threshold, last_updated FROM blood_stock",
        ['Blood Group','Units Available','Low Stock Threshold','Last Updated']
    ],
    'request' => [
        "SELECT br.request_id, br.patient_name, br.blood_group, br.required_units, h.hospital_name, br.emergency_level, br.request_date, br.status
         FROM blood_requests br LEFT JOIN hospitals h ON h.hospital_id = br.hospital_id
         WHERE br.request_date BETWEEN '$date_from' AND '$date_to'",
        ['ID','Patient','Blood Group','Units','Hospital','Emergency','Date','Status']
    ],
    'hospital' => [
        "SELECT hospital_id, hospital_name, registration_number, contact_person, mobile, city FROM hospitals WHERE created_at BETWEEN '$date_from 00:00:00' AND '$date_to 23:59:59'",
        ['ID','Name','Reg. No.','Contact Person','Mobile','City']
    ],
    'collection' => [
        "SELECT bc.collection_id, d.name, bc.blood_group, bc.units_collected, bc.collection_date, bc.expiry_date, bc.staff_name
         FROM blood_collection bc JOIN donors d ON d.donor_id = bc.donor_id
         WHERE bc.collection_date BETWEEN '$date_from' AND '$date_to'",
        ['ID','Donor','Blood Group','Units','Collection Date','Expiry','Staff']
    ],
    'issue' => [
        "SELECT bi.issue_id, bi.patient_name, bi.blood_group, bi.units_issued, h.hospital_name, bi.issue_date, bi.approved_by
         FROM blood_issue bi LEFT JOIN hospitals h ON h.hospital_id = bi.hospital_id
         WHERE bi.issue_date BETWEEN '$date_from' AND '$date_to'",
        ['ID','Patient','Blood Group','Units','Hospital','Date','Approved By']
    ],
];

if (!isset($map[$type])) { die('Unknown report type.'); }

[$sql, $headers] = $map[$type];
$result = mysqli_query($conn, $sql);

// Log this export in the reports audit table
mysqli_query($conn, "INSERT INTO reports (report_type, generated_by, date_from, date_to) VALUES ('$type', '" . clean($conn, $_SESSION['admin_name']) . "', '$date_from', '$date_to')");

header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $type . '_report_' . date('Y-m-d') . '.xls"');

$out = fopen('php://output', 'w');
fputcsv($out, $headers, "\t"); // tab-separated opens cleanly in Excel
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($out, $row, "\t");
}
fclose($out);
exit;

// NOTE: For a native .xlsx file instead of this tab-delimited .xls, install
// PhpSpreadsheet via Composer and swap this loop for its Xlsx writer.
