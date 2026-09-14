<?php
session_start();
unset($_SESSION['public_user_id']);
unset($_SESSION['public_user_name']);
session_destroy();
header('Location: /public/login.php');
exit;
