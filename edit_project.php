<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Project selector - Edit project</title>
  <link rel="stylesheet" href="styles/general.css" />
  <link rel="stylesheet" href="styles/colors.css" />
  <link rel="stylesheet" href="styles/style.css" />
  <link rel="stylesheet" href="styles/login-register.css" />
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
    <?php
    if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 2 || $_SESSION['user_role'] == 3)
    {
      $project_id = $_GET['id'];
      $project_id = filter_var($project_id, FILTER_SANITIZE_NUMBER_INT);
      $sql = 'SELECT * FROM projects WHERE id = ?';
      $stmt = mysqli_prepare($link, $sql);
      mysqli_stmt_bind_param($stmt, 'i', $project_id);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $row = mysqli_fetch_assoc($result);
      echo <<<HTML
        <h3 class="heading--tertiary">Edit project: $row[name]</h3>

        <form
        action="update_project.php"
        class="form center--align"
        method="post"
        enctype="multipart/form-data"
        >
            <input class="form__input" type="hidden" name="id" id="id" value="$row[id]" />
          <div class="form__field">
            <label class="form__label" for="name">Name</label>
            <input class="form__input" type="text" name="name" minlength="3" maxlength="32" id="name" value="$row[name]" />
          </div>
          <div class="form__field">
            <label class="form__label" for="description">Description</label>
            <input class="form__textarea" type="text" name="description" minlength="10" maxlength="256" id="description" value="$row[description]" >
          </div>
          <div class="form__field--btn">
            <button type="submit" class="form__button btn">Edit</button>
            <button type="reset" class="form__button btn">Reset</button>
          </div>
        </form>
        HTML;
    } else {
      echo '<h3>You are not allowed to see this page</h3>';
    }
    ?>
  </main>
</body>
</html>