<?php
require 'includes/bootstrap.php';
require_role(ROLE_ADMIN);

$users = query(
  'SELECT users.id, users.first_name, users.last_name, users.email, `groups`.name AS group_name, roles.name AS role_name
   FROM users
   LEFT JOIN `groups` ON `groups`.id = users.id_group
   LEFT JOIN roles ON roles.id = users.id_role
   ORDER BY users.id'
)->fetchAll();

$title = 'Admin';
require 'includes/header.php';
?>

<h1 class="heading--primary">Admin Panel</h1>

<div class="section__wrapper admin__wrapper">
  <section>
    <h2 class="heading--secondary">Users table</h2>
    <div class="table__wrapper">
      <table>
        <tr>
          <th>ID</th>
          <th>First name</th>
          <th>Last name</th>
          <th>Email</th>
          <th>Group</th>
          <th>Role</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
        <?php foreach ($users as $row): ?>
          <tr>
            <td><?= (int) $row['id'] ?></td>
            <td><?= e($row['first_name']) ?></td>
            <td><?= e($row['last_name']) ?></td>
            <td><?= e($row['email']) ?></td>
            <td><?= e($row['group_name'] ?? '-') ?></td>
            <td><?= e($row['role_name']) ?></td>
            <td><a href="edit_user-admin.php?id=<?= (int) $row['id'] ?>" class="lnk lnk--admin">Edit</a></td>
            <td>
              <?php if ($row['id'] !== current_user()['id']): ?>
                <form action="delete_user-admin.php" method="post" class="inline-form"
                  onsubmit="return confirm('Delete this user?')">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= (int) $row['id'] ?>" />
                  <button type="submit" class="lnk lnk--admin">Delete</button>
                </form>
              <?php endif ?>
            </td>
          </tr>
        <?php endforeach ?>
      </table>
    </div>
  </section>

  <section class="section__add-group">
    <h2 class="heading--secondary">Add group</h2>
    <form action="add_group-admin.php" method="post" class="form">
      <?= csrf_field() ?>
      <div>
        <label for="group_name" class="form__label">Group name:</label>
        <input type="text" name="group_name" class="form__input" id="group_name" placeholder="222/1"
          pattern="<?= GROUP_PATTERN ?>" title="Enter valid format: group/semi-group" required />
      </div>
      <button type="submit" class="form__button btn">Add</button>
    </form>
  </section>
</div>

<?php require 'includes/footer.php'; ?>
