<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Donor Offers';

$offers = mysqli_query($conn, "SELECT * FROM donation_offers ORDER BY created_at DESC");

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Donor Offers (from Public Portal)</h3>
<?php if ($msg = flash('success')): ?>
  <div class="alert alert-success alert-auto-dismiss"><?php echo e($msg); ?></div>
<?php endif; ?>
<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr><th>Name</th><th>Blood Group</th><th>Mobile</th><th>City</th><th>Available From</th><th>Message</th><th>Status</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($offers) === 0): ?>
          <tr><td colspan="8" class="text-center text-muted py-4">No donor offers yet.</td></tr>
        <?php endif; ?>
        <?php while ($o = mysqli_fetch_assoc($offers)): ?>
          <?php $ocolor = ['Pending'=>'warning','Contacted'=>'info','Completed'=>'success']; ?>
          <tr>
            <td><?php echo e($o['full_name']); ?></td>
            <td><span class="badge bg-success"><?php echo e($o['blood_group']); ?></span></td>
            <td><?php echo e($o['mobile']); ?></td>
            <td><?php echo e($o['city']); ?></td>
            <td><?php echo e($o['available_date']); ?></td>
            <td class="small text-muted"><?php echo e($o['message']); ?></td>
            <td><span class="badge bg-<?php echo $ocolor[$o['status']]; ?>"><?php echo e($o['status']); ?></span></td>
            <td class="text-nowrap">
              <?php if ($o['status'] === 'Pending'): ?>
                <a href="mark_status.php?id=<?php echo $o['offer_id']; ?>&status=Contacted" class="btn btn-sm btn-info">Mark Contacted</a>
              <?php endif; ?>
              <?php if ($o['status'] !== 'Completed'): ?>
                <a href="mark_status.php?id=<?php echo $o['offer_id']; ?>&status=Completed" class="btn btn-sm btn-success">Mark Completed</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
