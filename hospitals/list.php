<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Hospitals';
$search = clean($conn, $_GET['search'] ?? '');

$where_sql = '';
if ($search !== '') {
    $where_sql = "WHERE hospital_name LIKE '%$search%' OR city LIKE '%$search%' OR registration_number LIKE '%$search%'";
}

$hospitals = mysqli_query($conn, "SELECT * FROM hospitals $where_sql ORDER BY hospital_name");

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3>Hospital Management</h3>
  <a href="add.php" class="btn btn-danger"><i class="bi bi-plus-lg"></i> Add Hospital</a>
</div>
<?php if ($msg = flash('success')): ?>
  <div class="alert alert-success alert-auto-dismiss"><?php echo e($msg); ?></div>
<?php endif; ?>

<div class="card shadow-sm mb-3">
  <div class="card-body">
    <form method="GET" class="row g-2">
      <div class="col-md-10">
        <input type="text" name="search" class="form-control" placeholder="Search by hospital name, city, or registration number" value="<?php echo e($search); ?>">
      </div>
      <div class="col-md-2"><button class="btn btn-outline-danger w-100" type="submit">Search</button></div>
    </form>
  </div>
</div>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr><th>ID</th><th>Hospital Name</th><th>Reg. No.</th><th>Contact Person</th><th>Mobile</th><th>City</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($hospitals) === 0): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">No hospitals found.</td></tr>
        <?php endif; ?>
        <?php while ($h = mysqli_fetch_assoc($hospitals)): ?>
          <tr>
            <td>#<?php echo $h['hospital_id']; ?></td>
            <td><?php echo e($h['hospital_name']); ?></td>
            <td><?php echo e($h['registration_number']); ?></td>
            <td><?php echo e($h['contact_person']); ?></td>
            <td><?php echo e($h['mobile']); ?></td>
            <td><?php echo e($h['city']); ?></td>
            <td class="text-nowrap">
              <a href="edit.php?id=<?php echo $h['hospital_id']; ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
              <a href="delete.php?id=<?php echo $h['hospital_id']; ?>" class="btn btn-sm btn-outline-danger btn-delete-confirm"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
