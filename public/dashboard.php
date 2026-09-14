<?php
require_once '../includes/public_auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'My Dashboard';
$user_id = (int) $_SESSION['public_user_id'];

$my_requests = mysqli_query($conn, "SELECT * FROM blood_requests WHERE user_id = $user_id ORDER BY created_at DESC");
$my_offers = mysqli_query($conn, "SELECT * FROM donation_offers WHERE user_id = $user_id ORDER BY created_at DESC");

include '../includes/public_header.php';
?>
<h3 class="mb-4">Welcome, <?php echo e($_SESSION['public_user_name']); ?></h3>

<?php if ($msg = flash('success')): ?>
  <div class="alert alert-success alert-auto-dismiss"><?php echo e($msg); ?></div>
<?php endif; ?>

<div class="row g-3 mb-4">
  <div class="col-md-6">
    <div class="card shadow-sm h-100 border-danger">
      <div class="card-body text-center">
        <i class="bi bi-droplet-half text-danger" style="font-size:2.5rem;"></i>
        <h5 class="mt-2">I Need Blood</h5>
        <p class="text-muted small">Submit a request — it goes straight to the blood bank team.</p>
        <a href="request_blood.php" class="btn btn-danger">Request Blood</a>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card shadow-sm h-100 border-success">
      <div class="card-body text-center">
        <i class="bi bi-heart-fill text-success" style="font-size:2.5rem;"></i>
        <h5 class="mt-2">I Want to Donate</h5>
        <p class="text-muted small">Let us know you're willing to donate — the team will contact you.</p>
        <a href="donate_blood.php" class="btn btn-success">Offer to Donate</a>
      </div>
    </div>
  </div>
</div>

<h5 class="mb-3">My Blood Requests</h5>
<div class="card shadow-sm mb-4">
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr><th>Patient</th><th>Blood Group</th><th>Units</th><th>Emergency</th><th>Date</th><th>Status</th></tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($my_requests) === 0): ?>
          <tr><td colspan="6" class="text-center text-muted py-3">You haven't submitted any requests yet.</td></tr>
        <?php endif; ?>
        <?php while ($r = mysqli_fetch_assoc($my_requests)): ?>
          <?php $scolor = ['Pending'=>'warning','Approved'=>'success','Rejected'=>'danger']; ?>
          <tr>
            <td><?php echo e($r['patient_name']); ?></td>
            <td><span class="badge bg-danger"><?php echo e($r['blood_group']); ?></span></td>
            <td><?php echo e($r['required_units']); ?></td>
            <td><?php echo e($r['emergency_level']); ?></td>
            <td><?php echo e($r['request_date']); ?></td>
            <td><span class="badge bg-<?php echo $scolor[$r['status']]; ?>"><?php echo e($r['status']); ?></span></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<h5 class="mb-3">My Donation Offers</h5>
<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr><th>Blood Group</th><th>City</th><th>Available Date</th><th>Status</th></tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($my_offers) === 0): ?>
          <tr><td colspan="4" class="text-center text-muted py-3">You haven't offered to donate yet.</td></tr>
        <?php endif; ?>
        <?php while ($o = mysqli_fetch_assoc($my_offers)): ?>
          <?php $ocolor = ['Pending'=>'warning','Contacted'=>'info','Completed'=>'success']; ?>
          <tr>
            <td><span class="badge bg-success"><?php echo e($o['blood_group']); ?></span></td>
            <td><?php echo e($o['city']); ?></td>
            <td><?php echo e($o['available_date']); ?></td>
            <td><span class="badge bg-<?php echo $ocolor[$o['status']]; ?>"><?php echo e($o['status']); ?></span></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/public_footer.php'; ?>
