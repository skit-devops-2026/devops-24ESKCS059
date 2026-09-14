<?php
// Helper to mark active link
function nav_active($needle) {
    return (strpos($_SERVER['REQUEST_URI'], $needle) !== false) ? 'active' : '';
}
?>
<div class="sidebar bg-dark text-white p-3" style="width:240px; min-height:calc(100vh - 56px);">
  <ul class="nav nav-pills flex-column gap-1">
    <li class="nav-item"><a class="nav-link text-white <?php echo nav_active('/admin/dashboard'); ?>" href="/bloodbank_management_system/bloodbank/admin/dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo nav_active('/donors/'); ?>" href="/bloodbank_management_system/bloodbank/donors/list.php"><i class="bi bi-people-fill"></i> Donors</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo nav_active('/blood_stock/'); ?>" href="/bloodbank_management_system/bloodbank/blood_stock/list.php"><i class="bi bi-droplet-half"></i> Blood Stock</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo nav_active('/donations/'); ?>" href="/bloodbank_management_system/bloodbank/donations/list.php"><i class="bi bi-heart-pulse-fill"></i> Donations (Collection)</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo nav_active('/issue/'); ?>" href="/bloodbank_management_system/bloodbank/issue/list.php"><i class="bi bi-box-arrow-up-right"></i> Blood Issue</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo nav_active('/requests/'); ?>" href="/bloodbank_management_system/bloodbank/requests/list.php"><i class="bi bi-clipboard2-pulse-fill"></i> Blood Requests</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo nav_active('/donor_offers/'); ?>" href="/bloodbank_management_system/bloodbank/donor_offers/list.php"><i class="bi bi-hand-thumbs-up-fill"></i> Donor Offers</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo nav_active('/hospitals/'); ?>" href="/bloodbank_management_system/bloodbank/hospitals/list.php"><i class="bi bi-hospital-fill"></i> Hospitals</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo nav_active('/search/'); ?>" href="/bloodbank_management_system/bloodbank/search/search.php"><i class="bi bi-search"></i> Blood Search</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo nav_active('/reports/'); ?>" href="/bloodbank_management_system/bloodbank/reports/index.php"><i class="bi bi-bar-chart-fill"></i> Reports</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo nav_active('/notifications/'); ?>" href="/bloodbank_management_system/bloodbank/notifications/list.php"><i class="bi bi-bell-fill"></i> Notifications</a></li>
    <hr class="text-white-50">
    <li class="nav-item"><a class="nav-link text-white <?php echo nav_active('/admin/profile'); ?>" href="/bloodbank_management_system/bloodbank/admin/profile.php"><i class="bi bi-person-gear"></i> Profile</a></li>
    <li class="nav-item"><a class="nav-link text-white <?php echo nav_active('/admin/change_password'); ?>" href="/bloodbank_management_system/bloodbank/admin/change_password.php"><i class="bi bi-key-fill"></i> Change Password</a></li>
  </ul>
</div>
<div class="content-area p-4 flex-grow-1">
