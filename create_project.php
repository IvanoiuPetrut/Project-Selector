<?php
require 'includes/bootstrap.php';
require_role(ROLE_TEACHER, ROLE_ADMIN);
require_post();

$name = input('project_name');
$description = input('project_description');

if (mb_strlen($name) < 3 || mb_strlen($name) > 32 || mb_strlen($description) < 10 || mb_strlen($description) > 256) {
  flash('errors', 'Name must be 3-32 characters and description 10-256 characters');
  redirect('projects.php');
}

try {
  query('INSERT INTO projects (name, description) VALUES (?, ?)', [$name, $description]);
} catch (PDOException $e) {
  if (!is_duplicate_key($e)) {
    throw $e;
  }
  flash('errors', 'Project name already in use');
  redirect('projects.php');
}

flash('success', 'Project added');
redirect('projects.php');
