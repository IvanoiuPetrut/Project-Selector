<?php
require 'includes/bootstrap.php';
require_role(ROLE_TEACHER, ROLE_ADMIN);

$project = query('SELECT * FROM projects WHERE id = ?', [input_int('id', $_GET)])->fetch();
if (!$project) {
  flash('errors', 'Project not found');
  redirect('projects.php');
}

$title = 'Edit project';
$styles = ['login-register'];
require 'includes/header.php';
?>

<h3 class="heading--tertiary">Edit project: <?= e($project['name']) ?></h3>

<form action="update_project.php" class="form center--align" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= (int) $project['id'] ?>" />
  <div class="form__field">
    <label class="form__label" for="name">Name</label>
    <input class="form__input" type="text" name="name" minlength="3" maxlength="32" id="name"
      value="<?= e($project['name']) ?>" required />
  </div>
  <div class="form__field">
    <label class="form__label" for="description">Description</label>
    <textarea class="form__textarea" name="description" minlength="10" maxlength="256" id="description"
      required><?= e($project['description']) ?></textarea>
  </div>
  <div class="form__field--btn">
    <button type="submit" class="form__button btn">Edit</button>
    <button type="reset" class="form__button btn">Reset</button>
  </div>
</form>

<?php require 'includes/footer.php'; ?>
