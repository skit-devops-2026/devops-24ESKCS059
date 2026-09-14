<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

// "Delete Blood Stock" resets a group's count to zero rather than removing the row,
// since blood_stock always keeps exactly one row per blood group (see schema).
$id = (int) ($_GET['id'] ?? 0);
mysqli_query($conn, "UPDATE blood_stock SET units_available=0, last_updated=NOW() WHERE stock_id=$id");

flash('success', 'Stock reset to 0 units.');
redirect('list.php');
