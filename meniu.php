<?php
  session_start();
  include 'connect.php';
?>


<?php
echo <<<HTML
  <li><a href="index.php">Home</a></li>
  <li><a href="projects.php">Projects</a></li>
HTML;

if(!isset($_SESSION['user_id'])) {
  echo <<<HTML
  <li><a href="login.php">Login</a></li>
  <li><a href="register.php">Register</a></li>
HTML;
}

if(isset($_SESSION['user_id'])){
  echo <<<HTML
  <li><a href="settings.php">Settings</a></li>
  <li><a href="logout.php">Logout</a></li>
HTML;
}
?>