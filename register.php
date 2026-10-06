<?php
require 'includes/bootstrap.php';

$title = 'Register';
$styles = ['login-register'];
require 'includes/header.php';
?>

<?php if (current_user()): ?>
  <p>You are already logged in.</p>
<?php else: ?>
  <form action="create_account.php" class="form center--align" method="post">
    <?= csrf_field() ?>
    <div class="form__field">
      <label class="form__label" for="first_name">First Name</label>
      <input class="form__input" type="text" name="first_name" pattern="<?= NAME_PATTERN ?>" id="first_name"
        placeholder="First Name" required />
    </div>
    <div class="form__field">
      <label class="form__label" for="last_name">Last Name</label>
      <input class="form__input" type="text" name="last_name" pattern="<?= NAME_PATTERN ?>" id="last_name"
        placeholder="Last Name" required />
    </div>
    <div class="form__field">
      <label class="form__label" for="group">Group</label>
      <input class="form__input" type="text" name="group" pattern="<?= GROUP_PATTERN ?>" title="group/semi-group"
        id="group" placeholder="222/1" required />
    </div>
    <div class="form__field">
      <label class="form__label" for="email">E-Mail</label>
      <input class="form__input" name="email" type="email" id="email" placeholder="E-mail" autocomplete="email" required />
    </div>
    <div class="form__field">
      <label class="form__label" for="password">Password</label>
      <input class="form__input" name="password" type="password" pattern="<?= PASSWORD_PATTERN ?>"
        title="<?= PASSWORD_HINT ?>" id="password" placeholder="Password" autocomplete="new-password" required />
    </div>
    <div class="form__field--btn">
      <button type="submit" class="form__button btn">Submit</button>
      <button type="reset" class="form__button btn">Reset</button>
    </div>
  </form>
<?php endif ?>

<?php require 'includes/footer.php'; ?>
