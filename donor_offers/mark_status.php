<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);
$status = clean($conn, $_GET['status'] ?? '');

if (in_array($status, ['Pending', 'Contacted', 'Completed'], true)) {
    mysqli_query($conn, "UPDATE donation_offers SET status='$status' WHERE offer_id=$id");
    flash('success', 'Donor offer updated.');
}

redirect('list.php');
