<?php
// Include this at the top of every protected public-portal page.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['public_user_id'])) {
    redirect('/bloodbank_management_system/bloodbank/public/login.php');
}
