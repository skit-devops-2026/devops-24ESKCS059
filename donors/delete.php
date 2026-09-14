<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);
mysqli_query($conn, "DELETE FROM donors WHERE donor_id=$id");

flash('success', 'Donor deleted successfully.');
redirect('list.php');
