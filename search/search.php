<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Blood Search';

$blood_group = clean($conn, $_GET['blood_group'] ?? '');
$donor_name = clean($conn, $_GET['donor_name'] ?? '');
$city = clean($conn, $_GET['city'] ?? '');
$hospital = clean($conn, $_GET['hospital'] ?? '');
$mobile = clean($conn, $_GET['mobile'] ?? '');

$has_search = $blood_group || $donor_name || $city || $hospital || $mobile;
$donor_results = null;
$hospital_results = null;

if ($has_search) {
    $where = [];
    if ($blood_group !== '') $where[] = "blood_group = '$blood_group'";
    if ($donor_name !== '') $where[] = "name LIKE '%$donor_name%'";
    if ($city !== '') $where[] = "city LIKE '%$city%'";
    if ($mobile !== '') $where[] = "mobile LIKE '%$mobile%'";
    $where[] = "availability_status = 'Available'";
    $where_sql = 'WHERE ' . implode(' AND ', $where);
    $donor_results = mysqli_query($conn, "SELECT * FROM donors $where_sql ORDER BY name");

    if ($hospital !== '') {
        $h_clean = clean($conn, $hospital);
        $hospital_results = mysqli_query($conn, "SELECT * FROM hospitals WHERE hospital_name LIKE '%$h_clean%' OR city LIKE '%$h_clean%'");
    }
}

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<h3 class="mb-4">Blood Search</h3>

<div class="card shadow-sm mb-4">
  <div class="card-body">
    <form method="GET" class="row g-2">
      <div class="col-md-2">
        <select name="blood_group" class="form-select">
          <option value="">Blood Group</option>
          <?php foreach (blood_groups() as $bg): ?>
            <option value="<?php echo $bg; ?>" <?php echo $blood_group===$bg?'selected':''; ?>><?php echo $bg; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2"><input type="text" name="donor_name" class="form-control" placeholder="Donor Name" value="<?php echo e($donor_name); ?>"></div>
      <div class="col-md-2"><input type="text" name="city" class="form-control" placeholder="City" value="<?php echo e($city); ?>"></div>
      <div class="col-md-2"><input type="text" name="hospital" class="form-control" placeholder="Hospital" value="<?php echo e($hospital); ?>"></div>
      <div class="col-md-2"><input type="text" name="mobile" class="form-control" placeholder="Mobile Number" value="<?php echo e($mobile); ?>"></div>
      <div class="col-md-2"><button class="btn btn-danger w-100" type="submit"><i class="bi bi-search"></i> Search</button></div>
    </form>
  </div>
</div>

<?php if ($has_search): ?>
  <div class="card shadow-sm mb-4">
    <div class="card-header">Matching Available Donors</div>
    <div class="table-responsive">
      <table class="table mb-0">
        <thead class="table-light"><tr><th>Name</th><th>Blood Group</th><th>Mobile</th><th>City</th></tr></thead>
        <tbody>
          <?php if (!$donor_results || mysqli_num_rows($donor_results) === 0): ?>
            <tr><td colspan="4" class="text-center text-muted py-3">No matching donors.</td></tr>
          <?php endif; ?>
          <?php if ($donor_results): while ($d = mysqli_fetch_assoc($donor_results)): ?>
            <tr>
              <td><?php echo e($d['name']); ?></td>
              <td><span class="badge bg-danger"><?php echo e($d['blood_group']); ?></span></td>
              <td><?php echo e($d['mobile']); ?></td>
              <td><?php echo e($d['city']); ?></td>
            </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php if ($hospital !== ''): ?>
  <div class="card shadow-sm">
    <div class="card-header">Matching Hospitals</div>
    <div class="table-responsive">
      <table class="table mb-0">
        <thead class="table-light"><tr><th>Name</th><th>City</th><th>Mobile</th></tr></thead>
        <tbody>
          <?php if (!$hospital_results || mysqli_num_rows($hospital_results) === 0): ?>
            <tr><td colspan="3" class="text-center text-muted py-3">No matching hospitals.</td></tr>
          <?php endif; ?>
          <?php if ($hospital_results): while ($h = mysqli_fetch_assoc($hospital_results)): ?>
            <tr>
              <td><?php echo e($h['hospital_name']); ?></td>
              <td><?php echo e($h['city']); ?></td>
              <td><?php echo e($h['mobile']); ?></td>
            </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>
<?php else: ?>
  <p class="text-muted">Enter at least one criterion above to search.</p>
<?php endif; ?>
<?php include '../includes/footer.php'; ?>
