<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);
$req = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blood_requests WHERE request_id=$id"));

if (!$req) { redirect('list.php'); }

$stock_row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT units_available FROM blood_stock WHERE blood_group='{$req['blood_group']}'"));

if ($stock_row['units_available'] < $req['required_units']) {
    flash('error', "Cannot approve: only {$stock_row['units_available']} unit(s) of {$req['blood_group']} in stock (needs {$req['required_units']}).");
    redirect('list.php');
}

mysqli_query($conn, "UPDATE blood_requests SET status='Approved' WHERE request_id=$id");
adjust_stock($conn, $req['blood_group'], -$req['required_units']);

// Log it as an issue too, so it shows in reports/issue history
$patient = clean($conn, $req['patient_name']);
$bg = clean($conn, $req['blood_group']);
$units = (int) $req['required_units'];
$hospital_id = (int) $req['hospital_id'];
$approver = clean($conn, $_SESSION['admin_name']);
mysqli_query($conn, "INSERT INTO blood_issue (patient_name, blood_group, units_issued, hospital_id, issue_date, approved_by, remarks)
                      VALUES ('$patient', '$bg', $units, $hospital_id, CURDATE(), '$approver', 'Auto-issued on request approval')");

add_notification($conn, 'Request Approved', "Request #$id for {$req['patient_name']} approved.");

flash('success', 'Request approved and units issued.');
redirect('list.php');
