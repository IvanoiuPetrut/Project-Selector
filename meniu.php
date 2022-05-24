<?php
  session_start();
  include 'connect.php';
?>


<?php
echo <<<HTML
  <li><a href="index.php" class="lnk lnk--nav underline">Home</a></li>
  <li><a href="projects.php" class="lnk lnk--nav underline">Projects</a></li>
HTML;

if(!isset($_SESSION['user_id'])) {
  echo <<<HTML
  <li><a href="login.php" class="lnk lnk--nav underline">Login</a></li>
  <li><a href="register.php" class="lnk lnk--nav underline">Register</a></li>
HTML;
}

if(isset($_SESSION['user_id'])){
  echo <<<HTML
  <li><a href="profile.php" class="lnk lnk--nav underline">Profile</a></li>
  <li><a href="logout.php" class="lnk lnk--nav underline">Logout</a></li>
HTML;
}

if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 3) {
  echo <<<HTML
  <li><a href="admin.php" class="lnk lnk--nav underline">Admin</a></li>
HTML;
}
?>