<?php
require 'includes/bootstrap.php';

$user = current_user();
$can_manage = has_role(ROLE_TEACHER, ROLE_ADMIN);

$projects = query(
  'SELECT projects.*, COUNT(chosen_projects.id) AS takers
   FROM projects LEFT JOIN chosen_projects ON chosen_projects.id_project = projects.id
   GROUP BY projects.id ORDER BY projects.name'
)->fetchAll();

if (has_role(ROLE_STUDENT)) {
  // project id => status (0 in work, 1 finished) for this student
  $mine = query('SELECT id_project, status FROM chosen_projects WHERE id_user = ?', [$user['id']])
    ->fetchAll(PDO::FETCH_KEY_PAIR);
  // project id => how many students of this group took it
  $group_takers = query(
    'SELECT id_project, COUNT(*) FROM chosen_projects WHERE id_group = ? GROUP BY id_project',
    [$user['group']]
  )->fetchAll(PDO::FETCH_KEY_PAIR);
}

$title = 'Projects';
require 'includes/header.php';
?>

<div class="page-head">
  <div>
    <h1>Projects</h1>
    <p><?= count($projects) ?> project<?= count($projects) === 1 ? '' : 's' ?> available<?= has_role(ROLE_STUDENT) ? ' · up to ' . MAX_TAKERS_PER_GROUP . ' students per group can take each one' : '' ?></p>
  </div>
</div>

<div class="<?= $can_manage ? 'split' : '' ?>">
  <section>
    <?php if (!$projects): ?>
      <div class="card empty">
        <?= icon('folder') ?>
        <strong>No projects yet</strong>
        <span><?= $can_manage ? 'Add the first one with the form.' : 'Check back once your teacher posts some.' ?></span>
      </div>
    <?php endif ?>

    <div class="card-grid">
      <?php foreach ($projects as $project): ?>
        <article class="card project-card">
          <h3><?= e($project['name']) ?></h3>
          <p><?= e($project['description']) ?></p>

          <div class="project-card__footer">
            <?php if ($can_manage): ?>
              <span class="badge"><?= icon('users') ?><?= (int) $project['takers'] ?> taken</span>
              <div class="project-card__actions">
                <a href="edit_project.php?id=<?= (int) $project['id'] ?>" class="btn btn--ghost btn--icon btn--sm" title="Edit" aria-label="Edit <?= e($project['name']) ?>"><?= icon('pencil') ?></a>
                <form action="delete_project.php" method="post" class="inline-form" data-confirm="Delete this project? Students who took it will lose it too.">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= (int) $project['id'] ?>" />
                  <button type="submit" class="btn btn--ghost btn--danger btn--icon btn--sm" title="Delete" aria-label="Delete <?= e($project['name']) ?>"><?= icon('trash') ?></button>
                </form>
              </div>
            <?php elseif (has_role(ROLE_STUDENT)): ?>
              <?php $taken_in_group = (int) ($group_takers[$project['id']] ?? 0); ?>
              <span class="badge"><?= $taken_in_group ?>/<?= MAX_TAKERS_PER_GROUP ?> in your group</span>
              <?php if (isset($mine[$project['id']])): ?>
                <?= $mine[$project['id']]
                  ? '<span class="badge badge--success">' . icon('check') . 'Finished</span>'
                  : '<span class="badge badge--info">' . icon('clock') . 'In work</span>' ?>
              <?php elseif ($taken_in_group >= MAX_TAKERS_PER_GROUP): ?>
                <span class="badge">Full</span>
              <?php else: ?>
                <form action="add_project.php" method="post" class="inline-form">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= (int) $project['id'] ?>" />
                  <button type="submit" class="btn btn--primary btn--sm"><?= icon('plus') ?>Take</button>
                </form>
              <?php endif ?>
            <?php else: ?>
              <a href="login.php" class="btn btn--sm">Log in to take it</a>
            <?php endif ?>
          </div>
        </article>
      <?php endforeach ?>
    </div>
  </section>

  <?php if ($can_manage): ?>
    <aside class="card card--pad">
      <h2>Add a project</h2>
      <p class="card__subtitle">It shows up for every student right away.</p>
      <form action="create_project.php" class="form" method="post">
        <?= csrf_field() ?>
        <div class="field">
          <label for="project_name">Name</label>
          <input class="input" type="text" name="project_name" minlength="3" maxlength="32" id="project_name"
            placeholder="e.g. Library System" required />
        </div>
        <div class="field">
          <label for="project_description">Description</label>
          <textarea class="input" name="project_description" minlength="10" maxlength="256" id="project_description"
            placeholder="What should students build?" required></textarea>
          <span class="field__hint">10-256 characters</span>
        </div>
        <button type="submit" class="btn btn--primary btn--block"><?= icon('plus') ?>Add project</button>
      </form>
    </aside>
  <?php endif ?>
</div>

<?php require 'includes/footer.php'; ?>
