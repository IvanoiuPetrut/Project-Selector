<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Project Selector</title>
    <link rel="stylesheet" href="styles/general.css" />
    <link rel="stylesheet" href="styles/colors.css" />
    <link rel="stylesheet" href="styles/meniu.css" />
</head>

<body>
    <header>
        <nav class="nav">
            <ul class="nav__list">
            <?php
                include 'meniu.php';
            ?>
            </ul>
            <?php
                include 'logged-in.php'; 
            ?>
        </nav>
    </header>

    <html>
        <?php
        if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 3) {
            $sql = 'SELECT * FROM users';
            $result = $link->query($sql);
            if ($result->num_rows > 0) {
                echo '<table class="table" border="1">';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Fist name</th>';
                echo '<th>Last name</th>';
                echo '<th>Email</th>';
                echo '<th>Group ID</th>';
                echo '<th>Role ID</th>';
                echo '<th>Edit</th>';
                echo '<th>Delete</th>';
                echo '</tr>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td>' . $row['first_name'] . '</td>';
                    echo '<td>' . $row['last_name'] . '</td>';
                    echo '<td>' . $row['email'] . '</td>';
                    echo '<td>' . $row['id_group'] . '</td>';
                    echo '<td>' . $row['id_role'] . '</td>';
                    echo '<td><a href="edit_user-admin.php?id=' . $row['id'] . '">Edit</a></td>';
                    echo '<td><a href="delete_user-admin.php?id=' . $row['id'] . '">Delete</a></td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<p>No users found</p>';
            }

        }
        else {
            echo <<<HTML
            <p>You are not authorized to view this page.</p>
            HTML;
        }
            ?>
    </html>
</body>

</html>