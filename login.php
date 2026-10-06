<?php
require 'includes/bootstrap.php';

$title = 'Login';
$styles = ['login-register'];
require 'includes/header.php';
?>

<?php if (current_user()): ?>
  <p>You are already logged in.</p>
<?php else: ?>
  <form action="authentificator.php" class="form center--align" method="post">
    <?= csrf_field() ?>
    <div class="form__field">
      <label class="form__label" for="email">E-Mail</label>
      <input class="form__input" name="email" type="email" id="email" placeholder="E-mail" autocomplete="email" required />
    </div>
    <div class="form__field">
      <label class="form__label" for="password">Password</label>
      <input class="form__input" name="password" type="password" id="password" placeholder="Password" autocomplete="current-password" required />
    </div>
    <div class="form__field--btn">
      <button type="submit" class="form__button btn btn--primary">Submit</button>
      <button type="reset" class="form__button btn">Reset</button>
    </div>
  </form>
<?php endif ?>

<?php require 'includes/footer.php'; ?>
