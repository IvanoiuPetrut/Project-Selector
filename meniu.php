<?php
  // start session
  session_start();
  // unset session variables
?>


<?php
echo '
<li><a href="index.php">Home</a></li>
<li><a href="register.php">Register</a></li>
<li><a href="login.php">Login</a></li>
<li><a href="projects.php">Projects</a></li>';

if(isset($_SESSION['user_id'])){
  echo '<li><a href="logout.php">Logout</a></li>';
}
?>