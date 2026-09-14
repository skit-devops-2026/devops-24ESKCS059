<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$id = (int) ($_GET['id'] ?? 0);
mysqli_query($conn, "DELETE FROM hospitals WHERE hospital_id=$id");

flash('success', 'Hospital deleted successfully.');
redirect('list.php');
