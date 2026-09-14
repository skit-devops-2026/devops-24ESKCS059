<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? e($page_title) . ' - Blood Bank' : 'Blood Bank'; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="/bloodbank_management_system/bloodbank/assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-danger px-3">
  <a href="/bloodbank_management_system/bloodbank/public/dashboard.php" class="navbar-brand mb-0 h1 text-white text-decoration-none">
    <i class="bi bi-droplet-fill"></i> Blood Bank Management System
  </a>
  <div class="d-flex align-items-center gap-3">
    <?php if (isset($_SESSION['public_user_name'])): ?>
      <span class="text-white"><i class="bi bi-person-circle"></i> <?php echo e($_SESSION['public_user_name']); ?></span>
      <a href="/bloodbank_management_system/bloodbank/public/logout.php" class="btn btn-sm btn-outline-light">Logout</a>
    <?php endif; ?>
  </div>
</nav>
<div class="container py-4">
