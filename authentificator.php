<?php
require 'includes/bootstrap.php';
require_post();

$user = query('SELECT * FROM users WHERE email = ?', [input('email')])->fetch();

if (!$user || !verify_password($user, (string) ($_POST['password'] ?? ''))) {
  flash('errors', 'Wrong email or password');
  redirect('login.php');
}

login_user($user);
flash('success', 'You are now logged in');
redirect('index.php');
