<?php
require 'includes/bootstrap.php';

$user = current_user();
if ($user) {
  $project_count = (int) query('SELECT COUNT(*) FROM projects')->fetchColumn();
  if (has_role(ROLE_STUDENT)) {
    $counts = query(
      'SELECT SUM(status = 0) AS in_work, SUM(status = 1) AS finished FROM chosen_projects WHERE id_user = ?',
      [$user['id']]
    )->fetch();
  } else {
    $counts = query('SELECT SUM(status = 0) AS in_work, SUM(status = 1) AS finished FROM chosen_projects')->fetch();
  }
  $stats = [
    ['folder', '', $project_count, 'Projects available'],
    ['clock', 'info', (int) $counts['in_work'], has_role(ROLE_STUDENT) ? 'Your projects in work' : 'Assignments in work'],
    ['check-circle', 'success', (int) $counts['finished'], has_role(ROLE_STUDENT) ? 'You finished' : 'Assignments finished'],
  ];
}

$title = 'Home';
require 'includes/header.php';
?>

<?php if ($user): ?>
  <div class="page-head">
    <div>
      <h1>Welcome back, <?= e($user['name']) ?></h1>
      <p><?= has_role(ROLE_STUDENT) ? 'Pick a project or wrap up the ones you are working on.' : 'Here is how your students are doing.' ?></p>
    </div>
    <a href="projects.php" class="btn btn--primary">Browse projects <?= icon('arrow-right') ?></a>
  </div>

  <div class="stats">
    <?php foreach ($stats as [$stat_icon, $tone, $value, $label]): ?>
      <div class="card stat">
        <span class="stat__icon <?= $tone ? "stat__icon--$tone" : '' ?>"><?= icon($stat_icon) ?></span>
        <div>
          <div class="stat__value"><?= $value ?></div>
          <div class="stat__label"><?= e($label) ?></div>
        </div>
      </div>
    <?php endforeach ?>
  </div>

  <div class="features">
    <a href="profile.php" class="card feature">
      <span class="feature__icon"><?= icon('users') ?></span>
      <h3><?= has_role(ROLE_STUDENT) ? 'Your projects' : 'Student progress' ?></h3>
      <p><?= has_role(ROLE_STUDENT) ? 'Mark projects as finished when you are done.' : 'See who is working on what, and what is done.' ?></p>
    </a>
    <?php if (has_role(ROLE_ADMIN)): ?>
      <a href="admin.php" class="card feature">
        <span class="feature__icon"><?= icon('pencil') ?></span>
        <h3>Manage users</h3>
        <p>Edit accounts, change roles and add student groups.</p>
      </a>
    <?php endif ?>
  </div>
<?php else: ?>
  <section class="hero">
    <span class="badge badge--accent">For teachers and students</span>
    <h1>Semester projects, <span>picked and tracked</span> in one place</h1>
    <p>Teachers post project ideas, students choose what they want to build, and everyone sees progress until it is done.</p>
    <div class="hero__actions">
      <a href="register.php" class="btn btn--primary">Create an account <?= icon('arrow-right') ?></a>
      <a href="projects.php" class="btn">Browse projects</a>
    </div>
  </section>

  <div class="features">
    <div class="card feature">
      <span class="feature__icon"><?= icon('plus') ?></span>
      <h3>Teachers post projects</h3>
      <p>Describe a project once and it is available to every group.</p>
    </div>
    <div class="card feature">
      <span class="feature__icon"><?= icon('users') ?></span>
      <h3>Students pick one</h3>
      <p>Up to two students per group can take the same project.</p>
    </div>
    <div class="card feature">
      <span class="feature__icon"><?= icon('check-circle') ?></span>
      <h3>Track to the finish</h3>
      <p>See what is in work and what is finished at a glance.</p>
    </div>
  </div>
<?php endif ?>

<?php require 'includes/footer.php'; ?>
