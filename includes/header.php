<?php
// Expects $title (string) and optionally $styles (extra stylesheet names from styles/).
$styles ??= [];
$current_user = current_user();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Project Selector - <?= e($title) ?></title>
  <link rel="stylesheet" href="styles/general.css" />
  <link rel="stylesheet" href="styles/colors.css" />
  <link rel="stylesheet" href="styles/style.css" />
  <?php foreach ($styles as $style): ?>
    <link rel="stylesheet" href="styles/<?= e($style) ?>.css" />
  <?php endforeach ?>
</head>

<body>
  <header>
    <nav class="nav">
      <ul class="nav__list">
        <li><a href="index.php" class="lnk lnk--nav underline">Home</a></li>
        <li><a href="projects.php" class="lnk lnk--nav underline">Projects</a></li>
        <?php if ($current_user): ?>
          <li><a href="profile.php" class="lnk lnk--nav underline">Profile</a></li>
          <?php if (has_role(ROLE_ADMIN)): ?>
            <li><a href="admin.php" class="lnk lnk--nav underline">Admin</a></li>
          <?php endif ?>
          <li><a href="logout.php" class="lnk lnk--nav underline">Logout</a></li>
        <?php else: ?>
          <li><a href="login.php" class="lnk lnk--nav underline">Login</a></li>
          <li><a href="register.php" class="lnk lnk--nav underline">Register</a></li>
        <?php endif ?>
      </ul>
      <?php if ($current_user): ?>
        <p class="welcome-message">Welcome <span class="name"><?= e($current_user['name']) ?></span></p>
      <?php else: ?>
        <p class="welcome-message">You are not logged in</p>
      <?php endif ?>
    </nav>
  </header>

  <main class="main">
    <?php foreach (take_flashes() as $type => $messages): ?>
      <div class="<?= $type === 'success' ? 'success__wrapper' : 'errors__wrapper' ?>">
        <?php foreach ($messages as $message): ?>
          <p class="<?= $type === 'success' ? 'success' : 'error' ?>"><?= e($message) ?></p>
        <?php endforeach ?>
      </div>
    <?php endforeach ?>
