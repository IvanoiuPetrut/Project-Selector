<?php
require 'includes/bootstrap.php';
require_post();

$email = input('email');
$ip = client_ip();
$throttles = ["ip $ip" => LOGIN_MAX_FAILURES_PER_IP, 'account ' . $ip . ' ' . strtolower($email) => LOGIN_MAX_FAILURES_PER_ACCOUNT];

$allowed = true;
foreach ($throttles as $key => $max) {
  $allowed = begin_login_attempt($key, $max) && $allowed;
}
if (!$allowed) {
  flash('errors', 'Too many failed attempts, please try again in ' . LOGIN_WINDOW / 60 . ' minutes');
  redirect('login.php');
}

$user = query('SELECT * FROM users WHERE email = ?', [$email])->fetch();

if (!$user || !verify_password($user, (string) ($_POST['password'] ?? ''))) {
  flash('errors', 'Wrong email or password');
  redirect('login.php');
}

array_map('forget_login_attempt', array_keys($throttles));
login_user($user);
flash('success', 'You are now logged in');
redirect('index.php');
