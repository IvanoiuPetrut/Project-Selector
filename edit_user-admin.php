<?php
require 'includes/bootstrap.php';
require_role(ROLE_ADMIN);

$user = query('SELECT * FROM users WHERE id = ?', [input_int('id', $_GET)])->fetch();
if (!$user) {
  flash('errors', 'User not found');
  redirect('admin.php');
}
$groups = query('SELECT id, name FROM `groups` ORDER BY name')->fetchAll();
$roles = query('SELECT id, name FROM roles ORDER BY id')->fetchAll();

$title = 'Edit user';
require 'includes/header.php';
?>

<div class="auth auth--wide">
  <div class="auth__head">
    <h1>Edit user</h1>
    <p><?= e($user['first_name'] . ' ' . $user['last_name']) ?></p>
  </div>

  <div class="card card--pad">
    <form action="update_user-admin.php" class="form" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= (int) $user['id'] ?>" />
      <div class="form-row">
        <div class="field">
          <label for="first_name">First name</label>
          <input class="input" type="text" pattern="<?= NAME_PATTERN ?>" title="3-32 letters" name="first_name"
            id="first_name" value="<?= e($user['first_name']) ?>" required />
        </div>
        <div class="field">
          <label for="last_name">Last name</label>
          <input class="input" type="text" pattern="<?= NAME_PATTERN ?>" title="3-32 letters" name="last_name"
            id="last_name" value="<?= e($user['last_name']) ?>" required />
        </div>
      </div>
      <div class="field">
        <label for="email">E-mail</label>
        <input class="input" type="email" name="email" id="email" value="<?= e($user['email']) ?>" required />
      </div>
      <div class="form-row">
        <div class="field">
          <label for="group">Group</label>
          <select class="input" name="group" id="group">
            <option value="0">None</option>
            <?php foreach ($groups as $group): ?>
              <option value="<?= (int) $group['id'] ?>" <?= $group['id'] === $user['id_group'] ? 'selected' : '' ?>><?= e($group['name']) ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="field">
          <label for="role">Role</label>
          <select class="input" name="role" id="role">
            <?php foreach ($roles as $role): ?>
              <option value="<?= (int) $role['id'] ?>" <?= $role['id'] === $user['id_role'] ? 'selected' : '' ?>><?= e(ucfirst($role['name'])) ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>
      <div class="field">
        <label for="password">New password</label>
        <input class="input" type="password" pattern="<?= PASSWORD_PATTERN ?>" title="<?= PASSWORD_HINT ?>"
          name="password" id="password" autocomplete="new-password" />
        <span class="field__hint">Leave empty to keep the current password</span>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn--primary">Save changes</button>
        <a href="admin.php" class="btn btn--ghost">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php require 'includes/footer.php'; ?>
