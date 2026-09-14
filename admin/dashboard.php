<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Dashboard';

$total_donors   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM donors"))['c'];
$total_units    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(units_available) c FROM blood_stock"))['c'] ?? 0;
$total_requests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM blood_requests"))['c'];
$pending        = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM blood_requests WHERE status='Pending'"))['c'];
$approved       = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM blood_requests WHERE status='Approved'"))['c'];
$rejected       = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM blood_requests WHERE status='Rejected'"))['c'];
$total_hospitals= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM hospitals"))['c'];

$stock_result = mysqli_query($conn, "SELECT blood_group, units_available FROM blood_stock ORDER BY blood_group");
$stock_labels = []; $stock_values = [];
$stock_rows = [];
while ($row = mysqli_fetch_assoc($stock_result)) {
    $stock_labels[] = $row['blood_group'];
    $stock_values[] = (int)$row['units_available'];
    $stock_rows[] = $row;
}

$recent = mysqli_query($conn, "SELECT type, message, created_at FROM notifications ORDER BY created_at DESC LIMIT 6");

include '../includes/header.php';
include '../includes/sidebar.php';
?>

<h3 class="mb-4"><?php echo e($page_title); ?></h3>

<div class="row g-3 mb-4">
  <div class="col-md-3 col-sm-6">
    <div class="card card-stat shadow-sm"><div class="card-body">
      <div class="text-muted small">Total Donors</div>
      <div class="fs-3 fw-bold"><?php echo $total_donors; ?></div>
    </div></div>
  </div>
  <div class="col-md-3 col-sm-6">
    <div class="card card-stat shadow-sm"><div class="card-body">
      <div class="text-muted small">Total Blood Units</div>
      <div class="fs-3 fw-bold"><?php echo $total_units; ?></div>
    </div></div>
  </div>
  <div class="col-md-3 col-sm-6">
    <div class="card card-stat shadow-sm"><div class="card-body">
      <div class="text-muted small">Total Requests</div>
      <div class="fs-3 fw-bold"><?php echo $total_requests; ?></div>
    </div></div>
  </div>
  <div class="col-md-3 col-sm-6">
    <div class="card card-stat shadow-sm"><div class="card-body">
      <div class="text-muted small">Total Hospitals</div>
      <div class="fs-3 fw-bold"><?php echo $total_hospitals; ?></div>
    </div></div>
  </div>
  <div class="col-md-4 col-sm-6">
    <div class="card shadow-sm border-warning"><div class="card-body">
      <div class="text-muted small">Pending Requests</div>
      <div class="fs-3 fw-bold text-warning"><?php echo $pending; ?></div>
    </div></div>
  </div>
  <div class="col-md-4 col-sm-6">
    <div class="card shadow-sm border-success"><div class="card-body">
      <div class="text-muted small">Approved Requests</div>
      <div class="fs-3 fw-bold text-success"><?php echo $approved; ?></div>
    </div></div>
  </div>
  <div class="col-md-4 col-sm-6">
    <div class="card shadow-sm border-danger"><div class="card-body">
      <div class="text-muted small">Rejected Requests</div>
      <div class="fs-3 fw-bold text-danger"><?php echo $rejected; ?></div>
    </div></div>
  </div>
</div>

<div class="row g-3">
  <div class="col-md-7">
    <div class="card shadow-sm">
      <div class="card-header">Blood Availability</div>
      <div class="card-body">
        <canvas id="stockChart" height="120"></canvas>
      </div>
    </div>
  </div>
  <div class="col-md-5">
    <div class="card shadow-sm">
      <div class="card-header">Recent Activities</div>
      <ul class="list-group list-group-flush">
        <?php if (mysqli_num_rows($recent) === 0): ?>
          <li class="list-group-item text-muted">No recent activity.</li>
        <?php endif; ?>
        <?php while ($n = mysqli_fetch_assoc($recent)): ?>
          <li class="list-group-item">
            <span class="badge bg-secondary"><?php echo e($n['type']); ?></span>
            <?php echo e($n['message']); ?>
            <div class="small text-muted"><?php echo e($n['created_at']); ?></div>
          </li>
        <?php endwhile; ?>
      </ul>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('stockChart'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($stock_labels); ?>,
        datasets: [{
            label: 'Units Available',
            data: <?php echo json_encode($stock_values); ?>,
            backgroundColor: '#dc3545'
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>

<?php include '../includes/footer.php'; ?>
