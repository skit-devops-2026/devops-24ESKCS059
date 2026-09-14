<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? e($page_title) . ' - Blood Bank Management System' : 'Blood Bank Management System'; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="/bloodbank_management_system/bloodbank/assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-danger px-3">
  <span class="navbar-brand mb-0 h1"><i class="bi bi-droplet-fill"></i> Blood Bank Management System</span>
  <div class="d-flex align-items-center gap-3">
    <a href="/bloodbank_management_system/bloodbank/notifications/list.php" class="text-white position-relative">
      <i class="bi bi-bell-fill fs-5"></i>
      <?php if (isset($conn)) { $c = unread_notification_count($conn); if ($c > 0) { ?>
        <span class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle rounded-pill"><?php echo $c; ?></span>
      <?php } } ?>
    </a>
    <?php if (isset($_SESSION['admin_name'])): ?>
      <span class="text-white"><i class="bi bi-person-circle"></i> <?php echo e($_SESSION['admin_name']); ?></span>
      <a href="/bloodbank_management_system/bloodbank/admin/logout.php" class="btn btn-sm btn-outline-light">Logout</a>
    <?php endif; ?>
  </div>
</nav>
<div class="d-flex">
