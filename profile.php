<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="styles/general.css" />
    <link rel="stylesheet" href="styles/colors.css" />
    <link rel="stylesheet" href="styles/register.css" />
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
          include 'logged-in.php'; 
        ?>
    </nav>
  </header>

  <main>
    <h2>Settings</h2>
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

    echo $_SESSION['user_id'];
    echo $_SESSION['user_name'];

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
      <!-- <div class="form__field">
          <label class="form__label" for="id">ID</label> -->
          <input class="form__input" type="hidden" name="id" id="id" value="$row[id]" />
        <!-- </div> -->
        <div class="form__field">
          <label class="form__label" for="first_name">First name</label>
          <input class="form__input" type="text" name="first_name" id="first_name" value="$row[first_name]" />
        </div>
        <div class="form__field">
          <label class="form__label" for="last_name">Last name</label>
          <input class="form__input" type="text" name="last_name" id="last_name" value="$row[last_name]" />
        </div>
        <div class="form__field">
          <label class="form__label" for="email">Email</label>
          <input class="form__input" type="email" name="email" id="email" value="$row[email]" />
        </div>
        <div class="form__field">
          <label class="form__label" for="password">Password</label>
          <input class="form__input" type="password" name="password" id="password"/>
        </div>
        <div class="form__field">
          <label class="form__label" for="group">Group</label>
          <input class="form__input" type="text" name="group" id="group" value="$group_name[name]" />
        </div>
        <div class="form__field--btn">
          <button type="submit" class="form__button btn">Edit</button>
          <button type="reset" class="form__button btn">Reset</button>
        </div>
      </form>
      HTML;

      // check if is a student
      if(isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 1)) {
        // select current id for chosen project
        $sql = "SELECT * FROM chosen_projects WHERE id_user = '$user_id'";
        $result_chosen_project_id = $link -> query($sql);

        $sql = "SELECT projects.id, projects.name, projects.description FROM projects INNER JOIN chosen_projects ON projects.id = chosen_projects.id_project WHERE chosen_projects.id_user = '$user_id' and chosen_projects.status = 0";
        $result = $link -> query($sql);
        if($result -> num_rows > 0) {
          echo '<div class="container">';
          while($row = $result -> fetch_assoc()) {
            $row_chosen_projects = $result_chosen_project_id -> fetch_assoc();
            echo '<div class="project__wrapper">';
            echo '<div class="project">';
            echo '<h2 class="project__title accordion">Materie &mdash; ' . $row['name'] . '<ion-icon name="chevron-down-outline" class="icon icon-accordion"></ion-icon></h2>';
            echo '<p class="project__description">' . $row['description'] . '</p>';
            echo '</div>';

            if(isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 1)) {
                echo '<div class="project__buttons">';
                echo '<a href="complete_project.php?id=' . $row_chosen_projects['id'] . '" class="button button--primary">Complete project</a>';
                echo '</div>';
            }
            echo '</div>';
          }
          echo '</div>';
        } else {
          echo '<p>No projects</p>';
        }
      }
    } else {
      echo 'Nu esti autentificat';
    }

    // check if profesor
    if(isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 2 || ($_SESSION['user_role'] == 3))) {
      // in work projects
      echo '<h4> Projects &ndash; In work </h4>';
      $sql = "SELECT projects.name, projects.description, users.first_name, users.last_name FROM projects INNER JOIN chosen_projects ON projects.id = chosen_projects.id_project INNER JOIN users ON users.id = chosen_projects.id_user WHERE chosen_projects.status = 0";
      $result = $link -> query($sql);
      if($result -> num_rows > 0) {
        echo<<<HTML
        <table class="table" border="1">
        <tr>
          <th>First name</th>
          <th>Last name</th>
          <th>Project name</th>
          <th>Project description</th>
        </tr>
        HTML;
        while($row = $result -> fetch_assoc()) {
          echo <<<HTML
          <tr>
            <td>$row[first_name]</td>
            <td>$row[last_name]</td>
            <td>$row[name]</td>
            <td>$row[description]</td>
          HTML;
        }
      } else {
        echo '<p>No projects</p>';
      }
      
      //  finished projects
      echo <<<HTML
      <h4>Student &ndash; Finished</h4>
      HTML;
      $sql = "SELECT projects.name, projects.description, users.first_name, users.last_name FROM projects INNER JOIN chosen_projects ON projects.id = chosen_projects.id_project INNER JOIN users ON users.id = chosen_projects.id_user WHERE chosen_projects.status = 1";
      $result = $link -> query($sql);
      if($result -> num_rows > 0) {
        echo<<<HTML
        <table class="table" border="1">
        <tr>
          <th>First name</th>
          <th>Last name</th>
          <th>Project name</th>
          <th>Project description</th>
        </tr>
        HTML;
        while($row = $result -> fetch_assoc()) {
          echo <<<HTML
          <tr>
            <td>$row[first_name]</td>
            <td>$row[last_name]</td>
            <td>$row[name]</td>
            <td>$row[description]</td>
          HTML;
        }
      } else {
        echo '<p>No projects</p>';
      }
    }


    ?>

  </main>
</body>

</html>