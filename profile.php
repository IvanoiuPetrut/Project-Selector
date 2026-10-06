<?php
require 'includes/bootstrap.php';
require_role(ROLE_STUDENT, ROLE_TEACHER, ROLE_ADMIN);

$profile = query(
  'SELECT users.*, `groups`.name AS group_name FROM users LEFT JOIN `groups` ON `groups`.id = users.id_group WHERE users.id = ?',
  [current_user()['id']]
)->fetch();

if (has_role(ROLE_STUDENT)) {
  $my_projects = query(
    'SELECT chosen_projects.id AS chosen_id, projects.name, projects.description
     FROM chosen_projects INNER JOIN projects ON projects.id = chosen_projects.id_project
     WHERE chosen_projects.id_user = ? AND chosen_projects.status = 0',
    [$profile['id']]
  )->fetchAll();
} else {
  $assignments_sql = 'SELECT projects.name, users.first_name, users.last_name
    FROM chosen_projects
    INNER JOIN projects ON projects.id = chosen_projects.id_project
    INNER JOIN users ON users.id = chosen_projects.id_user
    WHERE chosen_projects.status = ?
    ORDER BY projects.name, users.last_name';
  $tables = [
    'Projects &ndash; In work' => query($assignments_sql, [0])->fetchAll(),
    'Projects &ndash; Finished' => query($assignments_sql, [1])->fetchAll(),
  ];
}

$title = 'Profile';
$styles = ['login-register'];
require 'includes/header.php';
?>

<h1 class="heading--primary">Profile</h1>

<div class="section__wrapper profile__wrapper">
  <section class="section__teacher">
    <?php if (has_role(ROLE_STUDENT)): ?>
      <h2 class="heading--secondary">Your projects</h2>
      <div class="projects__wrapper">
        <?php if (!$my_projects): ?>
          <p>No projects</p>
        <?php endif ?>
        <?php foreach ($my_projects as $project): ?>
          <div class="project__wrapper">
            <div class="project">
              <h2 class="heading--secondary"><?= e($project['name']) ?></h2>
              <p class="project__description"><?= e($project['description']) ?></p>
            </div>
            <div class="project__buttons">
              <form action="complete_project.php" method="post" class="inline-form">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int) $project['chosen_id'] ?>" />
                <button type="submit" class="lnk lnk--project lnk--green" title="Mark as completed">
                  <ion-icon name="checkmark-outline"></ion-icon>
                </button>
              </form>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    <?php else: ?>
      <h2 class="heading--secondary">Student projects</h2>
      <div class="projects__wrapper">
        <?php foreach ($tables as $heading => $rows): ?>
          <div class="project__table">
            <h4 class="heading--quaternary"><?= $heading ?></h4>
            <?php if ($rows): ?>
              <table class="table">
                <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Project Name</th>
                </tr>
                <?php foreach ($rows as $row): ?>
                  <tr>
                    <td><?= e($row['first_name']) ?></td>
                    <td><?= e($row['last_name']) ?></td>
                    <td><?= e($row['name']) ?></td>
                  </tr>
                <?php endforeach ?>
              </table>
            <?php else: ?>
              <p>None</p>
            <?php endif ?>
          </div>
        <?php endforeach ?>
      </div>
    <?php endif ?>
  </section>

  <section>
    <h2 class="heading--secondary">Update your profile</h2>
    <form action="update_user.php" class="form" method="post">
      <?= csrf_field() ?>
      <div class="form__field">
        <label class="form__label" for="first_name">First name</label>
        <input class="form__input" type="text" name="first_name" pattern="<?= NAME_PATTERN ?>" id="first_name"
          value="<?= e($profile['first_name']) ?>" required />
      </div>
      <div class="form__field">
        <label class="form__label" for="last_name">Last name</label>
        <input class="form__input" type="text" name="last_name" pattern="<?= NAME_PATTERN ?>" id="last_name"
          value="<?= e($profile['last_name']) ?>" required />
      </div>
      <?php if (has_role(ROLE_STUDENT)): ?>
        <div class="form__field">
          <label class="form__label" for="group">Group</label>
          <input class="form__input" type="text" name="group" pattern="<?= GROUP_PATTERN ?>" title="group/semi-group"
            id="group" value="<?= e($profile['group_name']) ?>" required />
        </div>
      <?php endif ?>
      <div class="form__field">
        <label class="form__label" for="email">Email</label>
        <input class="form__input" type="email" name="email" id="email" value="<?= e($profile['email']) ?>" required />
      </div>
      <div class="form__field">
        <label class="form__label" for="password">New password</label>
        <input class="form__input" type="password" name="password" pattern="<?= PASSWORD_PATTERN ?>"
          title="<?= PASSWORD_HINT ?>" id="password" placeholder="Leave empty to keep" autocomplete="new-password" />
      </div>
      <div class="form__field--btn">
        <button type="submit" class="form__button btn">Edit</button>
        <button type="reset" class="form__button btn">Reset</button>
      </div>
    </form>
  </section>
</div>

<?php require 'includes/footer.php'; ?>
