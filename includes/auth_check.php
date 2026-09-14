<?php
// Include this at the top of every protected admin page.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    redirect('/bloodbank_management_system/bloodbank/admin/login.php');
}
