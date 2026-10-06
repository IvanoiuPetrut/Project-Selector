<?php
require 'includes/bootstrap.php';
require_role(ROLE_STUDENT);
require_post();

// Only the student who took the project can mark it completed
$updated = query(
  'UPDATE chosen_projects SET status = 1 WHERE id = ? AND id_user = ?',
  [input_int('id'), current_user()['id']]
)->rowCount();

$updated ? flash('success', 'Project completed') : flash('errors', 'Project not found');
redirect('profile.php');
