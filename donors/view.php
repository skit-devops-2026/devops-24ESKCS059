<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Donor Details';
$id = (int) ($_GET['id'] ?? 0);
$donor = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM donors WHERE donor_id=$id"));
if (!$donor) { redirect('list.php'); }

$donations = mysqli_query($conn, "SELECT * FROM blood_collection WHERE donor_id=$id ORDER BY collection_date DESC");

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3>Donor Details</h3>
  <div>
    <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    <a href="list.php" class="btn btn-outline-secondary">Back</a>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-4">
    <div class="card shadow-sm text-center">
      <div class="card-body">
        <img src="<?php echo $donor['photo'] ? '/bloodbank_management_system/bloodbank/assets/images/donors/' . e($donor['photo']) : '/bloodbank_management_system/bloodbank/assets/images/donor_placeholder.svg'; ?>" class="rounded-circle mb-3" width="120" height="120" style="object-fit:cover;">
        <h5><?php echo e($donor['name']); ?></h5>
        <span class="badge bg-danger fs-6"><?php echo e($donor['blood_group']); ?></span>
        <p class="mt-2 mb-0">
          <?php echo $donor['availability_status']==='Available'
            ? '<span class="badge bg-success">Available</span>'
            : '<span class="badge bg-secondary">Not Available</span>'; ?>
        </p>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card shadow-sm">
      <div class="card-header">Personal & Contact Information</div>
      <table class="table mb-0">
        <tr><th style="width:220px;">Donor ID</th><td>#<?php echo $donor['donor_id']; ?></td></tr>
        <tr><th>Gender</th><td><?php echo e($donor['gender']); ?></td></tr>
        <tr><th>Date of Birth</th><td><?php echo e($donor['dob']); ?></td></tr>
        <tr><th>Age</th><td><?php echo e($donor['age']); ?></td></tr>
        <tr><th>Weight</th><td><?php echo e($donor['weight']); ?> kg</td></tr>
        <tr><th>Mobile</th><td><?php echo e($donor['mobile']); ?></td></tr>
        <tr><th>Email</th><td><?php echo e($donor['email']); ?></td></tr>
        <tr><th>Address</th><td><?php echo e($donor['address']); ?></td></tr>
        <tr><th>City / State</th><td><?php echo e($donor['city']); ?>, <?php echo e($donor['state']); ?></td></tr>
        <tr><th>Last Donation Date</th><td><?php echo e($donor['last_donation_date']) ?: '—'; ?></td></tr>
        <tr><th>Medical Status</th><td><?php echo e($donor['medical_status']); ?></td></tr>
      </table>
    </div>
  </div>
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header">Donation History</div>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead class="table-light"><tr><th>Date</th><th>Units</th><th>Expiry</th><th>Staff</th></tr></thead>
          <tbody>
            <?php if (mysqli_num_rows($donations) === 0): ?>
              <tr><td colspan="4" class="text-center text-muted py-3">No donation records yet.</td></tr>
            <?php endif; ?>
            <?php while ($row = mysqli_fetch_assoc($donations)): ?>
              <tr>
                <td><?php echo e($row['collection_date']); ?></td>
                <td><?php echo e($row['units_collected']); ?></td>
                <td><?php echo e($row['expiry_date']); ?></td>
                <td><?php echo e($row['staff_name']); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
