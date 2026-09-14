<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (isset($_SESSION['public_user_id'])) {
    redirect('/bloodbank_management_system/bloodbank/public/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = clean($conn, $_POST['full_name']);
    $email = clean($conn, $_POST['email']);
    $mobile = clean($conn, $_POST['mobile']);
    $blood_group = clean($conn, $_POST['blood_group']);
    $city = clean($conn, $_POST['city']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $check = mysqli_query($conn, "SELECT user_id FROM public_users WHERE email = '$email' LIMIT 1");
        if (mysqli_num_rows($check) > 0) {
            $error = 'An account with this email already exists. Please login instead.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO public_users (full_name, email, mobile, password, blood_group, city)
                VALUES ('$full_name', '$email', '$mobile', '$hash', '$blood_group', '$city')");

            $_SESSION['public_user_id'] = mysqli_insert_id($conn);
            $_SESSION['public_user_name'] = $full_name;
            redirect('/bloodbank_management_system/bloodbank/public/dashboard.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sign Up - Blood Bank</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="/bloodbank_management_system/bloodbank/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="login-wrapper">
  <div class="card shadow" style="width: 440px;">
    <div class="card-body p-4">
      <div class="text-center mb-3">
        <i class="bi bi-droplet-fill text-danger" style="font-size:3rem;"></i>
        <h4 class="mt-2">Create Your Account</h4>
      </div>
      <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo e($error); ?></div>
      <?php endif; ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="full_name" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Mobile Number</label>
          <input type="text" name="mobile" class="form-control" required>
        </div>
        <div class="row g-2 mb-3">
          <div class="col-md-6">
            <label class="form-label">Blood Group</label>
            <select name="blood_group" class="form-select" required>
              <?php foreach (blood_groups() as $bg): ?><option><?php echo $bg; ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" required>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required minlength="6">
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="confirm_password" class="form-control" required minlength="6">
        </div>
        <button type="submit" class="btn btn-danger w-100">Sign Up</button>
        <div class="text-center mt-3">
          Already have an account? <a href="login.php">Login</a>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
