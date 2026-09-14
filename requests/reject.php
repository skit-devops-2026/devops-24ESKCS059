<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);
$req = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM blood_requests WHERE request_id=$id"));
if (!$req) { redirect('list.php'); }

mysqli_query($conn, "UPDATE blood_requests SET status='Rejected' WHERE request_id=$id");
add_notification($conn, 'Request Rejected', "Request #$id for {$req['patient_name']} was rejected.");

flash('success', 'Request rejected.');
redirect('list.php');
