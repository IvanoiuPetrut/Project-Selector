<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Project Selector</title>
    <link rel="stylesheet" href="styles/general.css" />
    <link rel="stylesheet" href="styles/colors.css" />
    <link rel="stylesheet" href="styles/style.css" />
  </head>

  <body>
    <header>
      <nav class="nav">
        <ul class="nav__list">
          <?php
            include 'meniu.php';
          ?>
        </ul>
        <?php
          include 'logged-in.php'; 
        ?>
      </nav>
    </header>
    
      <?php
      if(isset($_SESSION['errors'])) {
        $errors = $_SESSION['errors'];
        echo '<div class="errors__wrapper">';
        foreach($errors as $error) {
          echo '<p class="error">' . $error[0] . '</p>';
        }
        echo '</div>';
        unset($_SESSION['errors']);
      }

      if(isset($_SESSION['success'])) {
        $success = $_SESSION['success'];
        echo '<div class="success__wrapper">';
        foreach($success as $succ) {
          echo '<p class="success">' . $succ[0] . '</p>';
        }
        echo '</div>';
        unset($_SESSION['success']);
      }
      ?>
  </body>
</html>
