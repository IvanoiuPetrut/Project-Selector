<?php
include 'connect.php';
global $link;

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="styles/style.css" />
</head>

<body>
  <main>
    <h1>Projects</h1>
    <!-- insert from php -->
    <?php
    $sql = 'SELECT * FROM projects';
    $result = $link->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo '<div class="project">';
            echo '<h2 class="project__title">Materie ' . $row['name'] . '</h2>';
            echo '<p class="project__description">' . $row['description'] . '</p>';
            echo '</div>';
        }
    } else {
        echo "0 results";
    }
    $link->close();

    ?>
    <button id="create-project-btn-open">Create project</button>
    <div class="modal" id="create-project-modal">
      <!-- form -->
      <form action="insert_data_from_project.php" class="form" method="post" enctype="multipart/form-data">
        <div class="form__field">
          <label class="form__label" for="project_name">Project Name</label>
          <input class="form__input" type="text" name="project_name" id="project_name" placeholder="Project Name"
            required />
        </div>
        <div class="form__field">
          <label class="form__label" for="project_description">Project Description</label>
          <textarea class="form__input" name="project_description" id="project_description"
            placeholder="Project Description" required></textarea>
        </div>
        <button type="submit" class="form__button">Submit</button>
      </form>
      <button id="create-project-btn-close">Close</button>
    </div>
    <div id="overlay" class="create-project-overlay"></div>
  </main>

  <script src="scripts/script.js"></script>
</body>

</html>
</php>