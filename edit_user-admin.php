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
$styles = ['login-register'];
require 'includes/header.php';
?>

<h3>Edit user <?= e($user['first_name'] . ' ' . $user['last_name']) ?></h3>

<form action="update_user-admin.php" class="form center--align" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="id" value="<?= (int) $user['id'] ?>" />
  <div class="form__field">
    <label class="form__label" for="first_name">First name</label>
    <input class="form__input" type="text" pattern="<?= NAME_PATTERN ?>" name="first_name" id="first_name"
      value="<?= e($user['first_name']) ?>" required />
  </div>
  <div class="form__field">
    <label class="form__label" for="last_name">Last name</label>
    <input class="form__input" type="text" pattern="<?= NAME_PATTERN ?>" name="last_name" id="last_name"
      value="<?= e($user['last_name']) ?>" required />
  </div>
  <div class="form__field">
    <label class="form__label" for="email">Email</label>
    <input class="form__input" type="email" name="email" id="email" value="<?= e($user['email']) ?>" required />
  </div>
  <div class="form__field">
    <label class="form__label" for="password">New password</label>
    <input class="form__input" type="password" pattern="<?= PASSWORD_PATTERN ?>" title="<?= PASSWORD_HINT ?>"
      name="password" id="password" placeholder="Leave empty to keep" autocomplete="new-password" />
  </div>
  <div class="form__field">
    <label class="form__label" for="group">Group</label>
    <select class="form__input" name="group" id="group">
      <option value="0">None</option>
      <?php foreach ($groups as $group): ?>
        <option value="<?= (int) $group['id'] ?>" <?= $group['id'] === $user['id_group'] ? 'selected' : '' ?>><?= e($group['name']) ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div class="form__field">
    <label class="form__label" for="role">Role</label>
    <select class="form__input" name="role" id="role">
      <?php foreach ($roles as $role): ?>
        <option value="<?= (int) $role['id'] ?>" <?= $role['id'] === $user['id_role'] ? 'selected' : '' ?>><?= e($role['name']) ?></option>
      <?php endforeach ?>
    </select>
  </div>
  <div class="form__field--btn">
    <button type="submit" class="form__button btn">Edit</button>
    <button type="reset" class="form__button btn">Reset</button>
  </div>
</form>

<?php require 'includes/footer.php'; ?>
