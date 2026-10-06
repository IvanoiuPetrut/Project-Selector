<?php
require 'includes/bootstrap.php';
require_role(ROLE_ADMIN);
require_post();

$id = input_int('id');
if ($id === current_user()['id']) {
  flash('errors', 'You cannot delete your own account');
} else {
  query('DELETE FROM users WHERE id = ?', [$id]);
  flash('success', 'User deleted');
}

redirect('admin.php');
