<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Project selector - Profile</title>
  <link rel="stylesheet" href="styles/general.css" />
  <link rel="stylesheet" href="styles/colors.css" />
  <link rel="stylesheet" href="styles/login-register.css" />
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

  <main class='main'>
    <h1 class="heading--primary">Profile</h1>
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

    <div class="section__wrapper profile__wrapper">
      <section class="section__teacher">
        <?php
        if($_SESSION['user_role']==1) {
          echo '<h2 class="heading--secondary">Your projects</h2>';
        } else {
          echo '<h2 class="heading--secondary">Student projects</h2>';
        }

        echo '<div class="projects__wrapper">';
        if(isset($_SESSION['user_id'])){
          $user_id = $_SESSION['user_id'];
          $sql = "SELECT * FROM users WHERE id = '$user_id'";
          $result = mysqli_query($link, $sql);
          $row = mysqli_fetch_assoc($result);
          $group_name = 'SELECT groups.name FROM groups WHERE id = ' . $row['id_group'];
          $group_name = mysqli_query($link, $group_name);
          $group_name = mysqli_fetch_assoc($group_name);
        }

        // check if is a student
        if(isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 1)) {
          // select current id for chosen project
          $sql = "SELECT * FROM chosen_projects WHERE id_user = '$user_id'";
          $result_chosen_project_id = $link -> query($sql);

          $sql = "SELECT projects.id, projects.name, projects.description FROM projects INNER JOIN chosen_projects ON projects.id = chosen_projects.id_project WHERE chosen_projects.id_user = '$user_id' and chosen_projects.status = 0";
          $result = $link -> query($sql);
          if($result -> num_rows > 0) {
            echo '<div class="prject__profile">';
            while($row = $result -> fetch_assoc()) {
              $row_chosen_projects = $result_chosen_project_id -> fetch_assoc();
              echo '<div class="project__wrapper">';
              echo '<div class="project">';
              echo '<h2 class="heading--secondary">' . $row['name'] . '</h2>';
              echo '<p class="project__description">' . $row['description'] . '</p>';
              echo '</div>';

              if(isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 1)) {
                  echo '<div class="project__buttons">';
                  echo '<a href="complete_project.php?id=' . $row_chosen_projects['id'] . '" class="lnk lnk--project lnk--green"><ion-icon name="checkmark-outline"></ion-icon></a>';
                  echo '</div>';
              }
              echo '</div>';
            }
            echo '</div>';
          } else {
            echo '<p>No projects</p>';
          }
        }
      
        echo '<div class="project__table">';
        if(isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 2 || ($_SESSION['user_role'] == 3))) {
          echo '<h4 class="heading--quaternary"> Projects &ndash; In work </h4>';
          $sql = "SELECT projects.name, users.first_name, users.last_name FROM projects INNER JOIN chosen_projects ON projects.id = chosen_projects.id_project INNER JOIN users ON users.id = chosen_projects.id_user WHERE chosen_projects.status = 0";
          $result = $link -> query($sql);
          if($result -> num_rows > 0) {
            echo <<<HTML
              <table class="table">
                <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Project Name</th>
                </tr>
            HTML;
            while($row = $result -> fetch_assoc()) {
              echo <<<HTML
                <tr>
                  <td>{$row['first_name']}</td>
                  <td>{$row['last_name']}</td>
                  <td>{$row['name']}</td>
                </tr>
              HTML;
            }
            echo '</table>';
        }
        echo '</div>';

        echo '<div class="project__table">';
        echo '<h4 class="heading--quaternary"> Projects &ndash; Finished </h4>';
          $sql = "SELECT projects.name, users.first_name, users.last_name FROM projects INNER JOIN chosen_projects ON projects.id = chosen_projects.id_project INNER JOIN users ON users.id = chosen_projects.id_user WHERE chosen_projects.status  = 1";
          $result = $link -> query($sql);
          if($result -> num_rows > 0) {
            echo <<<HTML
              <table class="table">
                <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Project Name</th>
                </tr>
            HTML;
            while($row = $result -> fetch_assoc()) {
              echo <<<HTML
                <tr>
                  <td>{$row['first_name']}</td>
                  <td>{$row['last_name']}</td>
                  <td>{$row['name']}</td>
                </tr>
              HTML;
            }
            echo '</table>';
        }
      }
      echo '</div>';
      echo '</div>';
      ?>
      </section>


      <section>
        <h2 class="heading--secondary">Update your profile</h2>
        <?php
        if(isset($_SESSION['user_id'])){
          $user_id = $_SESSION['user_id'];
          $sql = "SELECT * FROM users WHERE id = '$user_id'";
          $result = mysqli_query($link, $sql);
          $row = mysqli_fetch_assoc($result);
          $group_name = 'SELECT groups.name FROM groups WHERE id = ' . $row['id_group'];
          $group_name = mysqli_query($link, $group_name);
          $group_name = mysqli_fetch_assoc($group_name);
          echo <<<HTML
          <form
          action="update_user.php"
          class="form"
          method="post"
          enctype="multipart/form-data"
          >
            <input class="form__input" type="hidden" name="id" id="id" value="$row[id]" />
            <div class="form__field">
              <label class="form__label" for="first_name">First name</label>
              <input class="form__input" type="text" name="first_name" pattern="[A-Za-z]{3,32}" id="first_name" value="$row[first_name]" required/>
            </div>
            <div class="form__field">
              <label class="form__label" for="last_name">Last name</label>
              <input class="form__input" type="text" name="last_name" pattern="[A-Za-z]{3,32}" id="last_name" value="$row[last_name]" required/>
            </div>
            <div class="form__field">
              <label class="form__label" for="group">Group</label>
              <input class="form__input" type="text" name="group" pattern="[1-9]{3}/[1-9]{1}" title="group/semi-group" id="group" value="$group_name[name]" required/>
            </div>
            <div class="form__field">
              <label class="form__label" for="email">Email</label>
              <input class="form__input" type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
          title="Enter valid e-mail" id="email" value="$row[email]" required/>
            </div>
            <div class="form__field">
              <label class="form__label" for="password">Password</label>
              <input class="form__input" type="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,64}"
              title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" id="password"/>
            </div>
            <div class="form__field--btn">
              <button type="submit" class="form__button btn">Edit</button>
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
</body>

</html>