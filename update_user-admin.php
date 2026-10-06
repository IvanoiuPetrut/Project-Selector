<?php
require 'includes/bootstrap.php';
require_role(ROLE_ADMIN);
require_post();

$id = input_int('id');
$first_name = input('first_name');
$last_name = input('last_name');
$email = input('email');
$password = (string) ($_POST['password'] ?? '');
$group_id = input_int('group') ?: null;
$role_id = input_int('role');

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
if (!in_array($role_id, [ROLE_STUDENT, ROLE_TEACHER, ROLE_ADMIN], true)) {
  $errors[] = 'Unknown role';
}
if ($id === current_user()['id'] && $role_id !== ROLE_ADMIN) {
  $errors[] = 'You cannot remove your own admin role';
}

if ($errors) {
  array_walk($errors, fn($error) => flash('errors', $error));
  redirect('edit_user-admin.php?id=' . $id);
}

$fields = [
  'first_name' => normalize_name($first_name),
  'last_name' => normalize_name($last_name),
  'email' => $email,
  'id_group' => $group_id,
  'id_role' => $role_id,
];
if ($password !== '') {
  $fields['password'] = password_hash($password, PASSWORD_DEFAULT);
}

try {
  $assignments = implode(', ', array_map(fn($column) => "$column = ?", array_keys($fields)));
  query("UPDATE users SET $assignments WHERE id = ?", [...array_values($fields), $id]);
} catch (PDOException $e) {
  if (!is_duplicate_key($e)) {
    throw $e;
  }
  flash('errors', 'Email already in use');
  redirect('edit_user-admin.php?id=' . $id);
}

flash('success', 'User updated');
redirect('admin.php');
