<?php
require 'includes/bootstrap.php';
require_role(ROLE_ADMIN);

$users = query(
  'SELECT users.id, users.first_name, users.last_name, users.email, users.id_role, `groups`.name AS group_name, roles.name AS role_name
   FROM users
   LEFT JOIN `groups` ON `groups`.id = users.id_group
   LEFT JOIN roles ON roles.id = users.id_role
   ORDER BY users.id_role DESC, users.last_name'
)->fetchAll();
$groups = query(
  'SELECT `groups`.name, COUNT(users.id) AS members
   FROM `groups` LEFT JOIN users ON users.id_group = `groups`.id
   GROUP BY `groups`.id ORDER BY `groups`.name'
)->fetchAll();

$role_badge = [ROLE_STUDENT => '', ROLE_TEACHER => 'badge--info', ROLE_ADMIN => 'badge--accent'];

$title = 'Admin';
require 'includes/header.php';
?>

<div class="page-head">
  <div>
    <h1>Admin</h1>
    <p><?= count($users) ?> users · <?= count($groups) ?> groups</p>
  </div>
</div>

<div class="split">
  <section>
    <h2 class="section-title">Users</h2>
    <div class="card table-wrap">
      <table class="table">
        <tr>
          <th>Name</th>
          <th>Group</th>
          <th>Role</th>
          <th><span class="sr-only">Actions</span></th>
        </tr>
        <?php foreach ($users as $row): ?>
          <tr>
            <td>
              <span class="table__person"><?= e($row['first_name'] . ' ' . $row['last_name']) ?></span>
              <span class="table__sub"><?= e($row['email']) ?></span>
            </td>
            <td class="num"><?= e($row['group_name'] ?? '-') ?></td>
            <td><span class="badge <?= $role_badge[$row['id_role']] ?? '' ?>"><?= e(ucfirst($row['role_name'])) ?></span></td>
            <td class="actions">
              <a href="edit_user-admin.php?id=<?= (int) $row['id'] ?>" class="btn btn--ghost btn--icon btn--sm" title="Edit" aria-label="Edit <?= e($row['first_name']) ?>"><?= icon('pencil') ?></a>
              <?php if ($row['id'] !== current_user()['id']): ?>
                <form action="delete_user-admin.php" method="post" class="inline-form" data-confirm="Delete <?= e($row['first_name'] . ' ' . $row['last_name']) ?>? This cannot be undone.">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= (int) $row['id'] ?>" />
                  <button type="submit" class="btn btn--ghost btn--danger btn--icon btn--sm" title="Delete" aria-label="Delete <?= e($row['first_name']) ?>"><?= icon('trash') ?></button>
                </form>
              <?php endif ?>
            </td>
          </tr>
        <?php endforeach ?>
      </table>
    </div>
  </section>

  <aside class="stack">
    <div class="card card--pad">
      <h2>Add a group</h2>
      <p class="card__subtitle">Students choose their group when they sign up.</p>
      <form action="add_group-admin.php" method="post" class="form">
        <?= csrf_field() ?>
        <div class="field">
          <label for="group_name">Group name</label>
          <input type="text" name="group_name" class="input" id="group_name" placeholder="222/1"
            pattern="<?= GROUP_PATTERN ?>" title="group/semi-group, e.g. 222/1" required />
        </div>
        <button type="submit" class="btn btn--primary btn--block"><?= icon('plus') ?>Add group</button>
      </form>
    </div>

    <div class="card table-wrap">
      <table class="table">
        <tr>
          <th>Group</th>
          <th>Members</th>
        </tr>
        <?php foreach ($groups as $group): ?>
          <tr>
            <td class="table__person"><?= e($group['name']) ?></td>
            <td class="num"><?= (int) $group['members'] ?></td>
          </tr>
        <?php endforeach ?>
      </table>
    </div>
  </aside>
</div>

<?php require 'includes/footer.php'; ?>
