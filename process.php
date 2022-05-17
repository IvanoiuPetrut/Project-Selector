<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Untitled Document</title>
</head>
<body>
<style>
	.stil {
		font-family: "Courier New", Courier, monospace;
		font-size: medium;
		color: #FF0000;
	}
</style>
</head>
<body>
<?php
  // $_POST
  // $_GET

  var_dump($_POST);

	$prenume  = $_POST['first_name'];

	echo 'Prenume: <span class="stil">' . $prenume . '</span><br>' . "\n";
?>
</body>
</html>
