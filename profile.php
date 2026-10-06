<?php
require 'includes/bootstrap.php';
require_role(ROLE_STUDENT, ROLE_TEACHER, ROLE_ADMIN);

$profile = query(
  'SELECT users.*, `groups`.name AS group_name, roles.name AS role_name
   FROM users
   LEFT JOIN `groups` ON `groups`.id = users.id_group
   LEFT JOIN roles ON roles.id = users.id_role
   WHERE users.id = ?',
  [current_user()['id']]
)->fetch();

if (has_role(ROLE_STUDENT)) {
  $my_projects = query(
    'SELECT chosen_projects.id AS chosen_id, chosen_projects.status, projects.name, projects.description
     FROM chosen_projects INNER JOIN projects ON projects.id = chosen_projects.id_project
     WHERE chosen_projects.id_user = ?
     ORDER BY chosen_projects.status, projects.name',
    [$profile['id']]
  )->fetchAll();
} else {
  $assignments_sql = 'SELECT projects.name, users.first_name, users.last_name, `groups`.name AS group_name
    FROM chosen_projects
    INNER JOIN projects ON projects.id = chosen_projects.id_project
    INNER JOIN users ON users.id = chosen_projects.id_user
    LEFT JOIN `groups` ON `groups`.id = chosen_projects.id_group
    WHERE chosen_projects.status = ?
    ORDER BY projects.name, users.last_name';
  $tabs = [
    'in-work' => ['In work', 'clock', query($assignments_sql, [0])->fetchAll()],
    'finished' => ['Finished', 'check-circle', query($assignments_sql, [1])->fetchAll()],
  ];
}

$title = 'Profile';
require 'includes/header.php';
?>

<div class="page-head">
  <div>
    <h1><?= e($profile['first_name'] . ' ' . $profile['last_name']) ?></h1>
    <p>
      <span class="badge badge--accent"><?= e(ucfirst($profile['role_name'])) ?></span>
      <?php if ($profile['group_name']): ?>
        <span class="badge">Group <?= e($profile['group_name']) ?></span>
      <?php endif ?>
    </p>
  </div>
</div>

<div class="split">
  <section>
    <?php if (has_role(ROLE_STUDENT)): ?>
      <h2 class="section-title">Your projects</h2>
      <?php if (!$my_projects): ?>
        <div class="card empty">
          <?= icon('folder') ?>
          <strong>You have not taken a project yet</strong>
          <a href="projects.php" class="btn btn--primary btn--sm">Browse projects</a>
        </div>
      <?php endif ?>
      <div class="card-grid">
        <?php foreach ($my_projects as $project): ?>
          <article class="card project-card">
            <h3><?= e($project['name']) ?></h3>
            <p><?= e($project['description']) ?></p>
            <div class="project-card__footer">
              <?php if ($project['status']): ?>
                <span class="badge badge--success"><?= icon('check') ?>Finished</span>
              <?php else: ?>
                <span class="badge badge--info"><?= icon('clock') ?>In work</span>
                <form action="complete_project.php" method="post" class="inline-form" data-confirm="Mark this project as finished?">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= (int) $project['chosen_id'] ?>" />
                  <button type="submit" class="btn btn--sm btn--success"><?= icon('check') ?>Mark finished</button>
                </form>
              <?php endif ?>
            </div>
          </article>
        <?php endforeach ?>
      </div>
    <?php else: ?>
      <h2 class="section-title">Student projects</h2>
      <div class="card">
        <div class="tabs" role="tablist">
          <?php foreach ($tabs as $id => [$label, $tab_icon, $rows]): ?>
            <button type="button" class="tab" role="tab" id="tab-<?= $id ?>" aria-controls="panel-<?= $id ?>"
              aria-selected="<?= $id === 'in-work' ? 'true' : 'false' ?>">
              <?= icon($tab_icon) ?><?= $label ?> <span class="badge"><?= count($rows) ?></span>
            </button>
          <?php endforeach ?>
        </div>
        <?php foreach ($tabs as $id => [$label, $tab_icon, $rows]): ?>
          <div id="panel-<?= $id ?>" role="tabpanel" aria-labelledby="tab-<?= $id ?>" <?= $id === 'in-work' ? '' : 'hidden' ?>>
            <?php if ($rows): ?>
              <div class="table-wrap">
                <table class="table">
                  <tr>
                    <th>Student</th>
                    <th>Group</th>
                    <th>Project</th>
                  </tr>
                  <?php foreach ($rows as $row): ?>
                    <tr>
                      <td class="table__person"><?= e($row['first_name'] . ' ' . $row['last_name']) ?></td>
                      <td class="num"><?= e($row['group_name'] ?? '-') ?></td>
                      <td><?= e($row['name']) ?></td>
                    </tr>
                  <?php endforeach ?>
                </table>
              </div>
            <?php else: ?>
              <div class="empty">
                <?= icon($tab_icon) ?>
                <span>No projects <?= strtolower($label) ?> yet</span>
              </div>
            <?php endif ?>
          </div>
        <?php endforeach ?>
      </div>
    <?php endif ?>
  </section>

  <aside class="card card--pad">
    <h2>Account details</h2>
    <p class="card__subtitle">Update your name, e-mail or password.</p>
    <form action="update_user.php" class="form" method="post">
      <?= csrf_field() ?>
      <div class="form-row">
        <div class="field">
          <label for="first_name">First name</label>
          <input class="input" type="text" name="first_name" pattern="<?= NAME_PATTERN ?>" title="3-32 letters"
            id="first_name" value="<?= e($profile['first_name']) ?>" required />
        </div>
        <div class="field">
          <label for="last_name">Last name</label>
          <input class="input" type="text" name="last_name" pattern="<?= NAME_PATTERN ?>" title="3-32 letters"
            id="last_name" value="<?= e($profile['last_name']) ?>" required />
        </div>
      </div>
      <?php if (has_role(ROLE_STUDENT)): ?>
        <div class="field">
          <label for="group">Group</label>
          <input class="input" type="text" name="group" pattern="<?= GROUP_PATTERN ?>" title="group/semi-group, e.g. 222/1"
            id="group" value="<?= e($profile['group_name']) ?>" required />
        </div>
      <?php endif ?>
      <div class="field">
        <label for="email">E-mail</label>
        <input class="input" type="email" name="email" id="email" value="<?= e($profile['email']) ?>" autocomplete="email" required />
      </div>
      <div class="field">
        <label for="password">New password</label>
        <input class="input" type="password" name="password" pattern="<?= PASSWORD_PATTERN ?>"
          title="<?= PASSWORD_HINT ?>" id="password" autocomplete="new-password" />
        <span class="field__hint">Leave empty to keep your current password</span>
      </div>
      <button type="submit" class="btn btn--primary btn--block">Save changes</button>
    </form>
  </aside>
</div>

<?php require 'includes/footer.php'; ?>
