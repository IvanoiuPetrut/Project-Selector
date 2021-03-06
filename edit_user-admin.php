<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Project Selector - Edit user</title>
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

  <main>
  <?php
  if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 3) {
  // get user id from url
  $user_id = $_GET['id'];
  $sql = "SELECT * FROM users WHERE id = '$user_id'";
  $result = mysqli_query($link, $sql);
  $row = mysqli_fetch_assoc($result);
    echo <<<HTML
      <h3>Edit user $row[first_name] $row[last_name]</h3>

      <form
      action="update_user-admin.php"
      class="form center--align"
      method="post"
      enctype="multipart/form-data"
      >
      <div class="form__field">
          <label class="form__label" for="id">ID</label>
          <input class="form__input" form__input type="number" name="id" id="id" value="$row[id]" />
        </div>
        <div class="form__field">
          <label class="form__label" for="first_name">First name</label>
          <input class="form__input" form__input type="text" pattern="[A-Za-z]{3,32}" name="first_name" id="first_name" value="$row[first_name]" />
        </div>
        <div class="form__field">
          <label class="form__label" for="last_name">Last name</label>
          <input class="form__input" form__input type="text" pattern="[A-Za-z]{3,32}" name="last_name" id="last_name" value="$row[last_name]" />
        </div>
        <div class="form__field">
          <label class="form__label" for="email">Email</label>
          <input class="form__input" form__input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
          title="Enter valid e-mail" name="email" id="email" value="$row[email]" />
        </div>
        <div class="form__field">
          <label class="form__label" for="password">Password</label>
          <input class="form__input" form__input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,64}"
          title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" name="password" id="password"/>
        </div>
        <div class="form__field">
          <label class="form__label" for="group">Group ID</label>
          <input class="form__input" form__input type="number" name="group" id="group" value="$row[id_group]" />
        </div>
        <div class="form__field">
          <label class="form__label" for="role">Role ID</label>
          <input class="form__input" form__input type="number" name="role" id="role" value="$row[id_role]" />
        </div>
        <div class="form__field--btn">
          <button type="submit" class="form__button btn">Edit</button>
          <button type="reset" class="form__button btn">Reset</button>
        </div>
      </form>
    HTML;
  } else {
    echo '<h3>You are not allowed to see this page!</h3>';
  }
  ?>
  </main>
</body>
</html>