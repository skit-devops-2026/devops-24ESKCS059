<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/functions.php';

$page_title = 'Donors';

$search = clean($conn, $_GET['search'] ?? '');
$bg_filter = clean($conn, $_GET['blood_group'] ?? '');
$city_filter = clean($conn, $_GET['city'] ?? '');

$where = [];
if ($search !== '') {
    $where[] = "(name LIKE '%$search%' OR mobile LIKE '%$search%' OR email LIKE '%$search%')";
}
if ($bg_filter !== '') {
    $where[] = "blood_group = '$bg_filter'";
}
if ($city_filter !== '') {
    $where[] = "city LIKE '%$city_filter%'";
}
$where_sql = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

$donors = mysqli_query($conn, "SELECT * FROM donors $where_sql ORDER BY donor_id DESC");

include '../includes/header.php';
include '../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h3>Donor Management</h3>
  <a href="add.php" class="btn btn-danger"><i class="bi bi-plus-lg"></i> Add Donor</a>
</div>

<?php if ($msg = flash('success')): ?>
  <div class="alert alert-success alert-auto-dismiss"><?php echo e($msg); ?></div>
<?php endif; ?>

<div class="card shadow-sm mb-3">
  <div class="card-body">
    <form method="GET" class="row g-2">
      <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search by name, mobile, email" value="<?php echo e($search); ?>">
      </div>
      <div class="col-md-3">
        <select name="blood_group" class="form-select">
          <option value="">All Blood Groups</option>
          <?php foreach (blood_groups() as $bg): ?>
            <option value="<?php echo $bg; ?>" <?php echo $bg_filter === $bg ? 'selected' : ''; ?>><?php echo $bg; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <input type="text" name="city" class="form-control" placeholder="City" value="<?php echo e($city_filter); ?>">
      </div>
      <div class="col-md-2">
        <button class="btn btn-outline-danger w-100" type="submit"><i class="bi bi-search"></i> Filter</button>
      </div>
    </form>
  </div>
</div>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover dataTable-simple mb-0">
      <thead class="table-light">
        <tr>
          <th>ID</th><th>Photo</th><th>Name</th><th>Gender</th><th>Age</th>
          <th>Blood Group</th><th>Mobile</th><th>City</th><th>Availability</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($donors) === 0): ?>
          <tr><td colspan="10" class="text-center text-muted py-4">No donors found.</td></tr>
        <?php endif; ?>
        <?php while ($d = mysqli_fetch_assoc($donors)): ?>
          <tr>
            <td>#<?php echo $d['donor_id']; ?></td>
            <td><img src="<?php echo $d['photo'] ? '/bloodbank_management_system/bloodbank/assets/images/donors/' . e($d['photo']) : '/bloodbank_management_system/bloodbank/assets/images/donor_placeholder.svg'; ?>" width="40" height="40" class="rounded-circle" style="object-fit:cover;"></td>
            <td><?php echo e($d['name']); ?></td>
            <td><?php echo e($d['gender']); ?></td>
            <td><?php echo e($d['age']); ?></td>
            <td><span class="badge bg-danger"><?php echo e($d['blood_group']); ?></span></td>
            <td><?php echo e($d['mobile']); ?></td>
            <td><?php echo e($d['city']); ?></td>
            <td>
              <?php if ($d['availability_status'] === 'Available'): ?>
                <span class="badge bg-success">Available</span>
              <?php else: ?>
                <span class="badge bg-secondary">Not Available</span>
              <?php endif; ?>
            </td>
            <td class="text-nowrap">
              <a href="view.php?id=<?php echo $d['donor_id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
              <a href="edit.php?id=<?php echo $d['donor_id']; ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
              <a href="delete.php?id=<?php echo $d['donor_id']; ?>" class="btn btn-sm btn-outline-danger btn-delete-confirm"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
