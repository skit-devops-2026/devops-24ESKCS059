<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header('Location: admin/dashboard.php');
    exit;
}
if (isset($_SESSION['public_user_id'])) {
    header('Location: public/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Blood Bank Management System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="/bloodbank_management_system/bloodbank/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="login-wrapper">
  <div class="card shadow" style="width: 420px;">
    <div class="card-body p-4 text-center">
      <i class="bi bi-droplet-fill text-danger" style="font-size:3.5rem;"></i>
      <h3 class="mt-2 mb-1">Blood Bank Management System</h3>
      <p class="text-muted mb-4">Request blood, offer to donate, or manage the blood bank.</p>

      <a href="/bloodbank_management_system/bloodbank/public/login.php" class="btn btn-danger w-100 mb-2">Sign In (Need / Donate Blood)</a>
      <a href="/bloodbank_management_system/bloodbank/public/register.php" class="btn btn-outline-danger w-100 mb-3">Create an Account</a>

      <hr>
      <a href="/bloodbank_management_system/bloodbank/admin/login.php" class="small text-muted">Admin Login</a>
    </div>
  </div>
</div>
</body>
</html>
