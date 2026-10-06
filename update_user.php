<?php
require 'includes/bootstrap.php';
require_role(ROLE_STUDENT, ROLE_TEACHER, ROLE_ADMIN);
require_post();

$user = current_user();
$first_name = input('first_name');
$last_name = input('last_name');
$email = input('email');
$password = (string) ($_POST['password'] ?? '');
// Only students pick their own group; teachers and admins keep theirs
$group_id = has_role(ROLE_STUDENT) ? find_group_id(input('group')) : $user['group'];

$errors = [];
if (!matches(NAME_PATTERN, $first_name) || !matches(NAME_PATTERN, $last_name)) {
  $errors[] = 'Names must be 3-32 letters';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errors[] = 'Enter a valid e-mail';
}
if ($password !== '' && !matches(PASSWORD_PATTERN, $password)) {
  $errors[] = 'Password: ' . PASSWORD_HINT;
}
if (has_role(ROLE_STUDENT) && $group_id === null) {
  $errors[] = 'Unknown group';
}

if ($errors) {
  array_walk($errors, fn($error) => flash('errors', $error));
  redirect('profile.php');
}

$fields = ['first_name' => normalize_name($first_name), 'last_name' => normalize_name($last_name), 'email' => $email, 'id_group' => $group_id];
if ($password !== '') {
  $fields['password'] = password_hash($password, PASSWORD_DEFAULT);
}

try {
  $assignments = implode(', ', array_map(fn($column) => "$column = ?", array_keys($fields)));
  query("UPDATE users SET $assignments WHERE id = ?", [...array_values($fields), $user['id']]);
} catch (PDOException $e) {
  if (!is_duplicate_key($e)) {
    throw $e;
  }
  flash('errors', 'Email already in use');
  redirect('profile.php');
}

$_SESSION['user']['name'] = $fields['first_name'];
$_SESSION['user']['group'] = $group_id;
flash('success', 'Profile updated');
redirect('profile.php');
