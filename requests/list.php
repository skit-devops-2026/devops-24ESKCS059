<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Blood Requests';
$search = clean($conn, $_GET['search'] ?? '');
$status_filter = clean($conn, $_GET['status'] ?? '');

$where = [];
if ($search !== '') {
    $where[] = "(br.patient_name LIKE '%$search%' OR br.mobile LIKE '%$search%' OR h.hospital_name LIKE '%$search%')";
}
if ($status_filter !== '') {
    $where[] = "br.status = '$status_filter'";
}
$where_sql = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$requests = mysqli_query($conn, "SELECT br.*, h.hospital_name FROM blood_requests br
    LEFT JOIN hospitals h ON h.hospital_id = br.hospital_id
    $where_sql ORDER BY br.request_date DESC");

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3>Blood Request Management</h3>
  <a href="create.php" class="btn btn-danger"><i class="bi bi-plus-lg"></i> New Request</a>
</div>
<?php if ($msg = flash('success')): ?>
  <div class="alert alert-success alert-auto-dismiss"><?php echo e($msg); ?></div>
<?php endif; ?>
<?php if ($err = flash('error')): ?>
  <div class="alert alert-danger"><?php echo e($err); ?></div>
<?php endif; ?>

<div class="card shadow-sm mb-3">
  <div class="card-body">
    <form method="GET" class="row g-2">
      <div class="col-md-6">
        <input type="text" name="search" class="form-control" placeholder="Search by patient, mobile, hospital" value="<?php echo e($search); ?>">
      </div>
      <div class="col-md-4">
        <select name="status" class="form-select">
          <option value="">All Statuses</option>
          <?php foreach (['Pending','Approved','Rejected'] as $s): ?>
            <option value="<?php echo $s; ?>" <?php echo $status_filter===$s?'selected':''; ?>><?php echo $s; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2"><button class="btn btn-outline-danger w-100" type="submit">Filter</button></div>
    </form>
  </div>
</div>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr><th>ID</th><th>Patient</th><th>Blood Group</th><th>Units</th><th>Hospital</th><th>Emergency</th><th>Date</th><th>Status</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($requests) === 0): ?>
          <tr><td colspan="9" class="text-center text-muted py-4">No requests found.</td></tr>
        <?php endif; ?>
        <?php while ($r = mysqli_fetch_assoc($requests)): ?>
          <tr>
            <td>#<?php echo $r['request_id']; ?></td>
            <td><?php echo e($r['patient_name']); ?></td>
            <td><span class="badge bg-danger"><?php echo e($r['blood_group']); ?></span></td>
            <td><?php echo e($r['required_units']); ?></td>
            <td><?php echo e($r['hospital_name'] ?? '—'); ?></td>
            <td>
              <?php
                $ecolor = ['Normal'=>'secondary','Urgent'=>'warning','Critical'=>'danger'];
                echo '<span class="badge bg-'.$ecolor[$r['emergency_level']].'">'.e($r['emergency_level']).'</span>';
              ?>
            </td>
            <td><?php echo e($r['request_date']); ?></td>
            <td>
              <?php
                $scolor = ['Pending'=>'warning','Approved'=>'success','Rejected'=>'danger'];
                echo '<span class="badge bg-'.$scolor[$r['status']].'">'.e($r['status']).'</span>';
              ?>
            </td>
            <td class="text-nowrap">
              <?php if ($r['status'] === 'Pending'): ?>
                <a href="approve.php?id=<?php echo $r['request_id']; ?>" class="btn btn-sm btn-success">Approve</a>
                <a href="reject.php?id=<?php echo $r['request_id']; ?>" class="btn btn-sm btn-outline-danger">Reject</a>
              <?php else: ?>
                <span class="text-muted small">No action</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
