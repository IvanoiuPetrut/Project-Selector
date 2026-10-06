<?php
require 'includes/bootstrap.php';
require_role(ROLE_TEACHER, ROLE_ADMIN);

$project = query('SELECT * FROM projects WHERE id = ?', [input_int('id', $_GET)])->fetch();
if (!$project) {
  flash('errors', 'Project not found');
  redirect('projects.php');
}

$title = 'Edit project';
require 'includes/header.php';
?>

<div class="auth auth--wide">
  <div class="auth__head">
    <h1>Edit project</h1>
    <p><?= e($project['name']) ?></p>
  </div>

  <div class="card card--pad">
    <form action="update_project.php" class="form" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int) $project['id'] ?>" />
      <div class="field">
        <label for="name">Name</label>
        <input class="input" type="text" name="name" minlength="3" maxlength="32" id="name"
          value="<?= e($project['name']) ?>" required />
      </div>
      <div class="field">
        <label for="description">Description</label>
        <textarea class="input" name="description" minlength="10" maxlength="256" id="description"
          required><?= e($project['description']) ?></textarea>
        <span class="field__hint">10-256 characters</span>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn--primary">Save changes</button>
        <a href="projects.php" class="btn btn--ghost">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php require 'includes/footer.php'; ?>
