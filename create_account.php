<?php
require 'includes/bootstrap.php';
require_post();

$first_name = input('first_name');
$last_name = input('last_name');
$email = input('email');
$password = (string) ($_POST['password'] ?? '');
$group_id = find_group_id(input('group'));

$errors = [];
if (!matches(NAME_PATTERN, $first_name) || !matches(NAME_PATTERN, $last_name)) {
  $errors[] = 'Names must be 3-32 letters';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errors[] = 'Enter a valid e-mail';
}
if (!matches(PASSWORD_PATTERN, $password)) {
  $errors[] = 'Password: ' . PASSWORD_HINT;
}
if ($group_id === null) {
  $errors[] = 'Unknown group';
}

if ($errors) {
  array_walk($errors, fn($error) => flash('errors', $error));
  redirect('register.php');
}

try {
  query(
    'INSERT INTO users (first_name, last_name, email, password, id_group, id_role) VALUES (?, ?, ?, ?, ?, ?)',
    [normalize_name($first_name), normalize_name($last_name), $email, password_hash($password, PASSWORD_DEFAULT), $group_id, ROLE_STUDENT]
  );
} catch (PDOException $e) {
  if (!is_duplicate_key($e)) {
    throw $e;
  }
  flash('errors', 'Email already exists');
  redirect('register.php');
}

flash('success', 'Registration successful');
redirect('login.php');
