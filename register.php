<?php
require 'includes/bootstrap.php';

if (current_user()) {
  redirect('index.php');
}

$title = 'Sign up';
require 'includes/header.php';
?>

<div class="auth">
  <div class="auth__head">
    <h1>Create your account</h1>
    <p>Student accounts can pick projects right away.</p>
  </div>

  <div class="card card--pad">
    <form action="create_account.php" class="form" method="post">
      <?= csrf_field() ?>
      <div class="form-row">
        <div class="field">
          <label for="first_name">First name</label>
          <input class="input" type="text" name="first_name" pattern="<?= NAME_PATTERN ?>" title="3-32 letters"
            id="first_name" autocomplete="given-name" required />
        </div>
        <div class="field">
          <label for="last_name">Last name</label>
          <input class="input" type="text" name="last_name" pattern="<?= NAME_PATTERN ?>" title="3-32 letters"
            id="last_name" autocomplete="family-name" required />
        </div>
      </div>
      <div class="field">
        <label for="group">Group</label>
        <input class="input" type="text" name="group" pattern="<?= GROUP_PATTERN ?>" title="group/semi-group, e.g. 222/1"
          id="group" placeholder="222/1" required />
        <span class="field__hint">Your group and semi-group, as given by your teacher</span>
      </div>
      <div class="field">
        <label for="email">E-mail</label>
        <input class="input" name="email" type="email" id="email" placeholder="you@example.com" autocomplete="email" required />
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input class="input" name="password" type="password" pattern="<?= PASSWORD_PATTERN ?>"
          title="<?= PASSWORD_HINT ?>" id="password" autocomplete="new-password" required />
        <span class="field__hint"><?= PASSWORD_HINT ?></span>
      </div>
      <button type="submit" class="btn btn--primary btn--block">Create account</button>
    </form>
  </div>

  <p class="auth__foot">Already have an account? <a href="login.php">Log in</a></p>
</div>

<?php require 'includes/footer.php'; ?>
