<?php
require 'includes/bootstrap.php';

if (current_user()) {
  redirect('index.php');
}

$title = 'Log in';
require 'includes/header.php';
?>

<div class="auth">
  <div class="auth__head">
    <h1>Welcome back</h1>
    <p>Log in to pick and track your projects.</p>
  </div>

  <div class="card card--pad">
    <form action="authentificator.php" class="form" method="post">
      <?= csrf_field() ?>
      <div class="field">
        <label for="email">E-mail</label>
        <input class="input" name="email" type="email" id="email" placeholder="you@example.com" autocomplete="email" required autofocus />
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input class="input" name="password" type="password" id="password" autocomplete="current-password" required />
      </div>
      <button type="submit" class="btn btn--primary btn--block">Log in</button>
    </form>
  </div>

  <p class="auth__foot">No account yet? <a href="register.php">Sign up</a></p>
</div>

<?php require 'includes/footer.php'; ?>
