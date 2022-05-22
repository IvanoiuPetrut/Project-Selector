

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="styles/general.css" />
  <link rel="stylesheet" href="styles/colors.css" />
  <link rel="stylesheet" href="styles/projects.css" />
  <link rel="stylesheet" href="styles/meniu.css" />

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
          if(isset($_SESSION['user_id'])) {
            echo '<span>Welcome ' . $_SESSION['user_name'] . '</span>';
          }
        ?>
    </nav>
  </header>

  <main>
    <h1 class="heading--primary">Projects</h1>
    <?php
      // insert session error array intro a new array and display it
      if(isset($_SESSION['errors'])) {
        $errors = $_SESSION['errors'];
        echo '<div class="errors__wrapper">';
        foreach($errors as $error) {
          echo '<p class="error">' . $error[0] . '</p>';
        }
        echo '</div>';
        unset($_SESSION['errors']);
      }

      // insert session success array intro a new array and display it
      if(isset($_SESSION['success'])) {
        $success = $_SESSION['success'];
        echo '<div class="success__wrapper">';
        foreach($success as $succ) {
          echo '<p class="success">' . $succ[0] . '</p>';
        }
        echo '</div>';
        unset($_SESSION['success']);
      }
      
    $sql = 'SELECT * FROM projects';
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        echo '<div class="container">';
        while ($row = $result->fetch_assoc()) {
            echo '<div class="project__wrapper">';
            echo '<div class="project">';
            echo '<h2 class="project__title accordion">Materie &mdash; ' . $row['name'] . '<ion-icon name="chevron-down-outline" class="icon icon-accordion"></ion-icon></h2>';
            echo '<p class="project__description">' . $row['description'] . '</p>';
            echo '</div>';
            
            // check if the user is a techer or a student
            if(isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 2 || $_SESSION['user_role'] == 3)) {
                echo '<div class="project__buttons">';
                echo '<a href="edit_project.php?id=' . $row['id'] . '" class="button button--primary">Edit</a>';
                echo '<a href="delete_project.php?id=' . $row['id'] . '" class="button button--primary">Delete</a>';
                echo '</div>';
            }

            if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1) {
                echo '<div class="project__buttons">';
                echo '<a href="add_project.php?id=' . $row['id'] . '" class="button button--primary">Add project</a>';
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

    <button id="create-project-btn-open">Create project</button>
    <?php
          if(isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 2 || $_SESSION['user_role'] == 3)) {
            echo <<<HTML
            <div class="modal" id="create-project-modal">
              <!-- form -->
              <form action="insert_data_from_project.php" class="form" method="post" enctype="multipart/form-data">
                <div class="form__field">
                  <label class="form__label" for="project_name">Project Name</label>
                  <input class="form__input" type="text" name="project_name" id="project_name" placeholder="Project Name"
                    required />
                </div>
                <div class="form__field">
                  <label class="form__label" for="project_description">Project Description</label>
                  <textarea class="form__textarea" name="project_description" id="project_description"
                    placeholder="Project Description" required></textarea>
                </div>
                <button type="submit" class="form__button">Submit</button>
              </form>
              <button id="create-project-btn-close">Close</button>
            </div>
            <div id="overlay" class="create-project-overlay"></div>
HTML;
          }
    ?>
  </main>

  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="scripts/script.js"></script>
</body>

</html>
</php>