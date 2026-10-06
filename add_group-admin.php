<?php
require 'includes/bootstrap.php';
require_role(ROLE_ADMIN);
require_post();

$name = input('group_name');
if (!matches(GROUP_PATTERN, $name)) {
  flash('errors', 'Group must look like 222/1');
  redirect('admin.php');
}

try {
  query('INSERT INTO `groups` (name) VALUES (?)', [$name]);
  flash('success', 'Group added');
} catch (PDOException $e) {
  if (!is_duplicate_key($e)) {
    throw $e;
  }
  flash('errors', 'Group already exists');
}

redirect('admin.php');
