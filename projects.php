<?php
require 'includes/bootstrap.php';

$projects = query('SELECT * FROM projects ORDER BY name')->fetchAll();
$can_manage = has_role(ROLE_TEACHER, ROLE_ADMIN);

$title = 'Projects';
$styles = ['login-register'];
require 'includes/header.php';
?>

<h1 class="heading--primary">Projects</h1>

<div class="section__wrapper">
  <section>
    <?php if (!$projects): ?>
      <p>No projects yet</p>
    <?php endif ?>
    <div>
      <?php foreach ($projects as $project): ?>
        <div class="project__wrapper">
          <div class="project">
            <h2 class="heading--secondary"><?= e($project['name']) ?></h2>
            <p class="project__description"><?= e($project['description']) ?></p>
          </div>

          <?php if ($can_manage): ?>
            <div class="project__buttons">
              <a href="edit_project.php?id=<?= (int) $project['id'] ?>" class="lnk lnk--project lnk--green" title="Edit">
                <ion-icon name="create-outline" class="icon icon--project"></ion-icon>
              </a>
              <form action="delete_project.php" method="post" class="inline-form"
                onsubmit="return confirm('Delete this project?')">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int) $project['id'] ?>" />
                <button type="submit" class="lnk lnk--project lnk--red" title="Delete">
                  <ion-icon name="close-outline" class="icon icon--project"></ion-icon>
                </button>
              </form>
            </div>
          <?php elseif (has_role(ROLE_STUDENT)): ?>
            <div class="project__buttons">
              <form action="add_project.php" method="post" class="inline-form">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int) $project['id'] ?>" />
                <button type="submit" class="lnk lnk--project lnk--green" title="Take this project">
                  <ion-icon name="add-outline" class="icon icon--project"></ion-icon>
                </button>
              </form>
            </div>
          <?php endif ?>
        </div>
      <?php endforeach ?>
    </div>
  </section>

  <?php if ($can_manage): ?>
    <section class="section__add-project">
      <h2 class="heading--secondary">Add project</h2>
      <form action="create_project.php" class="form" method="post">
        <?= csrf_field() ?>
        <div class="form__field">
          <label class="form__label" for="project_name">Project Name</label>
          <input class="form__input" type="text" name="project_name" minlength="3" maxlength="32" id="project_name"
            placeholder="Project Name" required />
        </div>
        <div class="form__field">
          <label class="form__label" for="project_description">Project Description</label>
          <textarea class="form__textarea" name="project_description" minlength="10" maxlength="256"
            id="project_description" placeholder="Project Description" required></textarea>
        </div>
        <div class="form__field--btn">
          <button type="submit" class="form__button btn">Submit</button>
          <button type="reset" class="form__button btn">Reset</button>
        </div>
      </form>
    </section>
  <?php endif ?>
</div>

<?php require 'includes/footer.php'; ?>
