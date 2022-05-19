<?php
  include 'meniu.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Project Selector - Register</title>
    <link rel="stylesheet" href="styles/general.css" />
    <link rel="stylesheet" href="styles/colors.css" />
    <link rel="stylesheet" href="styles/register.css" />
  </head>
  <body>
    <form
      action="authentificator.php"
      class="form"
      method="post"
      enctype="multipart/form-data"
    >
      <div class="form__field">
        <label class="form__label" for="email">E-Mail</label>
        <input
          class="form__input"
          name="email"
          type="email"
          id="email"
          placeholder="E-mail"
        />
      </div>
      <div class="form__field">
        <label class="form__label" for="password">Password</label>
        <input
          class="form__input"
          name="password"
          type="password"
          id="password"
          placeholder="Password"
        />
      </div>
      <div class="form__field--btn">
        <button type="submit" class="form__button btn">Submit</button>
        <button type="reset" class="form__button btn">Reset</button>
      </div>
    </form>
  </body>
</html>
