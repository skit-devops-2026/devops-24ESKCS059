<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Notifications';

$notifications = mysqli_query($conn, "SELECT * FROM notifications ORDER BY created_at DESC LIMIT 100");

// Mark all as read once viewed
mysqli_query($conn, "UPDATE notifications SET is_read = 1 WHERE is_read = 0");

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Notifications</h3>
<div class="card shadow-sm">
  <ul class="list-group list-group-flush">
    <?php if (mysqli_num_rows($notifications) === 0): ?>
      <li class="list-group-item text-muted text-center py-4">No notifications yet.</li>
    <?php endif; ?>
    <?php
      $type_icons = [
        'New Request' => 'bi-clipboard2-pulse-fill text-primary',
        'Low Stock' => 'bi-exclamation-triangle-fill text-danger',
        'Expiry Alert' => 'bi-hourglass-split text-warning',
        'Request Approved' => 'bi-check-circle-fill text-success',
        'Request Rejected' => 'bi-x-circle-fill text-danger',
        'Donation Reminder' => 'bi-heart-pulse-fill text-danger',
      ];
    ?>
    <?php while ($n = mysqli_fetch_assoc($notifications)): ?>
      <li class="list-group-item d-flex align-items-start gap-3">
        <i class="bi <?php echo $type_icons[$n['type']] ?? 'bi-bell-fill'; ?> fs-5"></i>
        <div>
          <div class="fw-semibold"><?php echo e($n['type']); ?></div>
          <div><?php echo e($n['message']); ?></div>
          <div class="small text-muted"><?php echo e($n['created_at']); ?></div>
        </div>
      </li>
    <?php endwhile; ?>
  </ul>
</div>
<?php include '../includes/footer.php'; ?>
