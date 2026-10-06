<?php
require 'includes/bootstrap.php';
require_role(ROLE_STUDENT);
require_post();

const MAX_TAKERS_PER_GROUP = 2;

$user = current_user();
$project_id = input_int('id');

if (!query('SELECT 1 FROM projects WHERE id = ?', [$project_id])->fetchColumn()) {
  flash('errors', 'Project not found');
} elseif (query('SELECT COUNT(*) FROM chosen_projects WHERE id_user = ? AND id_project = ?', [$user['id'], $project_id])->fetchColumn() > 0) {
  flash('errors', 'You already took this project');
} elseif (query('SELECT COUNT(*) FROM chosen_projects WHERE id_group = ? AND id_project = ?', [$user['group'], $project_id])->fetchColumn() >= MAX_TAKERS_PER_GROUP) {
  flash('errors', 'Only ' . MAX_TAKERS_PER_GROUP . ' students per group can take a project');
} else {
  query(
    'INSERT INTO chosen_projects (id_user, id_group, id_project, status) VALUES (?, ?, ?, 0)',
    [$user['id'], $user['group'], $project_id]
  );
  flash('success', 'Project added');
}

redirect('projects.php');
