<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Reports';

$reports = [
    ['title' => 'Donor Report', 'desc' => 'All registered donors with contact & blood group.', 'url' => 'donor_report.php', 'icon' => 'bi-people-fill'],
    ['title' => 'Blood Stock Report', 'desc' => 'Current availability per blood group.', 'url' => 'stock_report.php', 'icon' => 'bi-droplet-half'],
    ['title' => 'Blood Request Report', 'desc' => 'All requests with status and emergency level.', 'url' => 'request_report.php', 'icon' => 'bi-clipboard2-pulse-fill'],
    ['title' => 'Hospital Report', 'desc' => 'Registered hospitals and their details.', 'url' => 'hospital_report.php', 'icon' => 'bi-hospital-fill'],
    ['title' => 'Blood Collection Report', 'desc' => 'Donations collected, by date range.', 'url' => 'collection_report.php', 'icon' => 'bi-heart-pulse-fill'],
    ['title' => 'Blood Issue Report', 'desc' => 'Units issued to hospitals/patients.', 'url' => 'issue_report.php', 'icon' => 'bi-box-arrow-up-right'],
];

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Reports</h3>
<div class="row g-3">
  <?php foreach ($reports as $r): ?>
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <i class="bi <?php echo $r['icon']; ?> fs-2 text-danger"></i>
          <h5 class="mt-2"><?php echo e($r['title']); ?></h5>
          <p class="text-muted small"><?php echo e($r['desc']); ?></p>
          <a href="<?php echo $r['url']; ?>" class="btn btn-outline-danger btn-sm">View Report</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<div class="card shadow-sm mt-4">
  <div class="card-header">Quick Ranges</div>
  <div class="card-body">
    <p class="text-muted mb-0">Each report below supports Daily, Monthly, and Yearly filters via the date-range fields at the top of the report, plus PDF / Excel / Print export.</p>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
