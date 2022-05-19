<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Project Selector</title>
    <link rel="stylesheet" href="styles/general.css" />
    <link rel="stylesheet" href="styles/colors.css" />
    <link rel="stylesheet" href="styles/meniu.css" />
  </head>

  <body>
    <header>
      <nav class="nav">
        <ul class="nav__lsit">
          <?php
            include 'meniu.php';
          ?>
        </ul>
        <?php
          if(isset($_SESSION['user_id'])){
            echo '<span>Welcome ' . $_SESSION['user_name'] . '</span>';
          }
        ?>
      </nav>
    </header>
  </body>
</html>
