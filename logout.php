<?php
require 'includes/bootstrap.php';

if (!current_user()) {
  redirect('index.php');
}
require_post();

$_SESSION = [];
session_regenerate_id(true);
redirect('index.php');
