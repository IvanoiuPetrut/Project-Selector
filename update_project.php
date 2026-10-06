<?php
require 'includes/bootstrap.php';
require_role(ROLE_TEACHER, ROLE_ADMIN);
require_post();

$id = input_int('id');
$name = input('name');
$description = input('description');

if (mb_strlen($name) < 3 || mb_strlen($name) > 32 || mb_strlen($description) < 10 || mb_strlen($description) > 256) {
  flash('errors', 'Name must be 3-32 characters and description 10-256 characters');
  redirect('edit_project.php?id=' . $id);
}

try {
  query('UPDATE projects SET name = ?, description = ? WHERE id = ?', [$name, $description, $id]);
} catch (PDOException $e) {
  if (!is_duplicate_key($e)) {
    throw $e;
  }
  flash('errors', 'Project name already in use');
  redirect('edit_project.php?id=' . $id);
}

flash('success', 'Project updated');
redirect('projects.php');
