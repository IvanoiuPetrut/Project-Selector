<?php
require 'includes/bootstrap.php';
require_role(ROLE_TEACHER, ROLE_ADMIN);
require_post();

query('DELETE FROM projects WHERE id = ?', [input_int('id')]);

flash('success', 'Project deleted');
redirect('projects.php');
