<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Project selector - Projects</title>
  <link rel="stylesheet" href="styles/general.css" />
  <link rel="stylesheet" href="styles/colors.css" />
  <link rel="stylesheet" href="styles/login-register.css" />
  <link rel="stylesheet" href="styles/style.css" />
  <!-- <link rel="stylesheet" href="styles/projects.css" /> -->

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

  <main class="main">
    <h1 class="heading--primary">Projects</h1>
    
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
    
    <div class="section__wrapper">
      <section>
        <?php
          $sql = 'SELECT * FROM projects';
          $result = $link->query($sql);
          if ($result->num_rows > 0) {
              // output data of each row
              echo '<div>';
              while ($row = $result->fetch_assoc()) {
                echo '<div class="project__wrapper">';
                echo '<div class="project">';
                echo '<h2 class="heading--secondary">' . $row['name'] . '</ion-icon></h2>';
                echo '<p class="project__description">' . $row['description'] . '</p>';
                echo '</div>';
                  
                if(isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 2 || $_SESSION['user_role'] == 3)) {
                  echo '<div class="project__buttons">';
                  echo '<a href="edit_project.php?id=' . $row['id'] . '" class="lnk lnk--project lnk--green"><ion-icon name="create-outline" class="icon icon--project"></ion-icon></a>';
                  echo '<a href="delete_project.php?id=' . $row['id'] . '" class="lnk lnk--project lnk--red"><ion-icon name="close-outline" class="icon icon--project"></ion-icon></a>';
                  echo '</div>';
                }

                if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1) {
                  echo '<div class="project__buttons">';
                  echo '<a href="add_project.php?id=' . $row['id'] . '" class="lnk lnk--project lnk--green"><ion-icon name="add-outline" class="icon icon--project"></ion-icon></a>';
                  echo '</div>';
                }
                  echo '</div>';
              }
              echo '</div>';
          } else {
              echo "0 results";
          }
          $link->close();
        ?>
      </section>

      <section>
        <?php
          if(isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 2 || $_SESSION['user_role'] == 3)) {
            echo <<<HTML
              <!-- form -->
              <form action="create_project.php" class="form" method="post" enctype="multipart/form-data">
                <div class="form__field">
                  <label class="form__label" for="project_name">Project Name</label>
                  <input class="form__input" type="text" name="project_name" minlength="3" maxlength="32" id="project_name" placeholder="Project Name"
                    required />
                </div>
                <div class="form__field">
                  <label class="form__label" for="project_description">Project Description</label>
                  <textarea class="form__textarea" name="project_description" minlength="10" maxlength="256" id="project_description"
                    placeholder="Project Description" required></textarea>
                </div>
                <div class="form__field--btn">
                  <button type="submit" class="form__button btn">Submit</button>
                  <button type="reset" class="form__button btn">Reset</button>
                </div>
              </form>
          HTML;
          }
      ?>
      </section>
    </div>
  </main>

  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="scripts/script.js"></script>
</body>

</html>