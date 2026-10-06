<?php
// Expects $title (string).
$current_user = current_user();
$current_page = basename($_SERVER['SCRIPT_NAME']);

$nav = ['index.php' => 'Home', 'projects.php' => 'Projects'];
if ($current_user) {
  $nav['profile.php'] = 'Profile';
  if (has_role(ROLE_ADMIN)) {
    $nav['admin.php'] = 'Admin';
  }
}
$active = match ($current_page) {
  'edit_project.php' => 'projects.php',
  'edit_user-admin.php' => 'admin.php',
  default => $current_page,
};
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($title) ?> · Project Selector</title>
  <script>
    try {
      const theme = localStorage.getItem("theme");
      if (theme) document.documentElement.dataset.theme = theme;
    } catch {}
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" />
  <link rel="stylesheet" href="styles/app.css" />
  <script src="scripts/app.js" defer></script>
</head>

<body>
  <header class="site-header">
    <div class="container site-header__inner">
      <a href="index.php" class="brand">
        <span class="brand__mark"><?= icon('logo') ?></span>
        <span class="brand__name">Project Selector</span>
      </a>

      <nav class="nav" aria-label="Main">
        <?php foreach ($nav as $href => $label): ?>
          <a href="<?= $href ?>" class="nav__link" <?= $href === $active ? 'aria-current="page"' : '' ?>><?= $label ?></a>
        <?php endforeach ?>
        <?php if (!$current_user): ?>
          <a href="login.php" class="nav__link nav__link--mobile">Log in</a>
          <a href="register.php" class="nav__link nav__link--mobile">Sign up</a>
        <?php endif ?>
      </nav>

      <div class="header-actions">
        <button type="button" class="btn btn--ghost btn--icon theme-toggle" aria-label="Toggle dark mode">
          <?= icon('moon', 'icon icon-moon') ?><?= icon('sun', 'icon icon-sun') ?>
        </button>
        <?php if ($current_user): ?>
          <span class="user-chip">
            <span class="avatar"><?= e(strtoupper($current_user['name'][0] ?? '?')) ?></span>
            <span><?= e($current_user['name']) ?></span>
          </span>
          <a href="logout.php" class="btn btn--ghost btn--icon" aria-label="Log out" title="Log out"><?= icon('logout') ?></a>
        <?php else: ?>
          <a href="login.php" class="btn btn--ghost auth-link">Log in</a>
          <a href="register.php" class="btn btn--primary auth-link">Sign up</a>
        <?php endif ?>
        <button type="button" class="btn btn--ghost btn--icon menu-toggle" aria-label="Menu" aria-expanded="false">
          <?= icon('menu') ?>
        </button>
      </div>
    </div>
  </header>

  <div class="toasts" aria-live="polite">
    <?php foreach (take_flashes() as $type => $messages): ?>
      <?php foreach ($messages as $message): ?>
        <div class="toast <?= $type === 'success' ? '' : 'toast--error' ?>" role="<?= $type === 'success' ? 'status' : 'alert' ?>">
          <?= icon($type === 'success' ? 'check-circle' : 'x') ?>
          <p><?= e($message) ?></p>
          <button type="button" aria-label="Dismiss"><?= icon('x') ?></button>
        </div>
      <?php endforeach ?>
    <?php endforeach ?>
  </div>

  <main class="main container">
