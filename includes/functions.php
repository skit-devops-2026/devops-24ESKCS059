<?php
// ============================================================
// Shared helper functions used across all modules
// ============================================================

function clean($conn, $value) {
    return mysqli_real_escape_string($conn, trim($value ?? ''));
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function flash($key, $message = null) {
    // set: flash('error', 'Something went wrong');
    // get:  $msg = flash('error');
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return;
    }
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

function e($string) {
    // shorthand output escaping for HTML
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function calculate_age($dob) {
    $birth = new DateTime($dob);
    $today = new DateTime('today');
    return $birth->diff($today)->y;
}

function blood_groups() {
    return ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
}

function add_notification($conn, $type, $message) {
    $type = clean($conn, $type);
    $message = clean($conn, $message);
    mysqli_query($conn, "INSERT INTO notifications (type, message) VALUES ('$type', '$message')");
}

function unread_notification_count($conn) {
    $res = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM notifications WHERE is_read = 0");
    $row = mysqli_fetch_assoc($res);
    return $row['cnt'] ?? 0;
}

function log_login($conn, $username, $role, $status) {
    $username = clean($conn, $username);
    $role = clean($conn, $role);
    $ip = clean($conn, $_SERVER['REMOTE_ADDR'] ?? 'unknown');
    mysqli_query($conn, "INSERT INTO login_history (username, role, ip_address, status)
                          VALUES ('$username', '$role', '$ip', '$status')");
}

// Adjusts blood_stock units_available by $delta (can be negative) for a blood group.
function adjust_stock($conn, $blood_group, $delta) {
    $blood_group = clean($conn, $blood_group);
    $delta = (int) $delta;
    mysqli_query($conn, "UPDATE blood_stock SET units_available = units_available + ($delta), last_updated = NOW()
                          WHERE blood_group = '$blood_group'");

    // low stock check
    $res = mysqli_query($conn, "SELECT units_available, low_stock_threshold FROM blood_stock WHERE blood_group = '$blood_group'");
    $row = mysqli_fetch_assoc($res);
    if ($row && $row['units_available'] <= $row['low_stock_threshold']) {
        add_notification($conn, 'Low Stock', "$blood_group stock is low: {$row['units_available']} units remaining.");
    }
}
