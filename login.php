<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Project Selector - Register</title>
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
          if(isset($_SESSION['user_id'])){
            echo '<span>Welcome ' . $_SESSION['user_name'] . '</span>';
          }
        ?>
    </nav>
  </header>

  <main>
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
    
      if(!isset($_SESSION['user_id'])){
        echo <<<HTML
            <form
            action="authentificator.php"
            class="form center--align"
            method="post"
            enctype="multipart/form-data"
            >
              <div class="form__field">
                <label class="form__label" for="email">E-Mail</label>
                <input
                  class="form__input"
                  name="email"
                  type="email"
                  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                  id="email"
                  placeholder="E-mail"
                  required
                />
              </div>
              <div class="form__field">
                <label class="form__label" for="password">Password</label>
                <input
                  class="form__input"
                  name="password"
                  type="password"
                  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,64}"
                  title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                  id="password"
                  placeholder="Password"
                  required
                />
              </div>
              <div class="form__field--btn">
                <button type="submit" class="form__button btn btn--primary">Submit</button>
                <button type="reset" class="form__button btn">Reset</button>
              </div>
          </form>
          HTML;
        }
        else {
          echo <<<HTML
          <p>Sunteti deja logat.</p>
          HTML;
        }
      ?>
      </main>

</body>

</html>